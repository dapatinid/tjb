<?php

namespace App\Filament\Resources\PorderResource\Pages;

use App\Filament\Resources\PorderResource;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Porder;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPorder extends EditRecord
{
    protected static string $resource = PorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $data['updated_by'] = Auth::user()->id;
    //     return $data;
    // }

    protected function beforeSave(): void 
    {$record = $this->record;
            if ($record->isDirty('date_order')) {
                $hari = Carbon::parse($record->date_order)->format('Y-m-d');
                $antri = Porder::where('branch_id', Auth::user()->branch_id)->where('date_order', 'like', "%$hari%")->count() + 1;
                Porder::where('id', $record->id)->update(['q' => $antri,]);
            }
            // hitung Hutang Pembelian untuk jurnal
            // if ($record->isDirty('grand_total')) {
            Payment::where('paymentable_type', Porder::class)
                ->where('paymentable_id', $record->id)
                ->where('mutation_type', "Hutang Pembelian")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $record->date_order,
                    'nominal_plus' => 0,
                    'nominal_mins' => $record->grand_total,
                    'nominal' => $record->grand_total,
                ]);
            // }
    }


    protected function afterSave(): void
    {$record = $this->record;
            // Update HPP tiap produk ketika melakukan Pembelian
            // $record = $this->record;
            $dataBelanja = OrderItem::where('porder_id', $record->id)->get();
            $orderitems = OrderItem::where('status', '!=', 'canceled');
            foreach ($dataBelanja as $item) {
                $boughtqty = $orderitems->where('product_id', $item->product_id)->sum('p_quantity');
                $soldqty = $orderitems->where('product_id', $item->product_id)->sum('quantity');
                $stokBefore_validate = $boughtqty - $soldqty - $item->p_quantity;
                if ($stokBefore_validate <= 0) {
                    $stokBefore = 0;
                } else {
                    $stokBefore = $stokBefore_validate;
                }
                $stokAdd = $item->p_quantity;
                $stokAfter = $stokBefore + $stokAdd;

                $itemUpdate = Product::where('id', $item->product_id);
                $hppLama = $itemUpdate->value('cogs');
                $hppBaru = ($item->p_unit_amount == 0) ? $hppLama : $item->p_unit_amount;
                $hppAvg = (($hppLama * $stokBefore) + ($hppBaru * $stokAdd)) / $stokAfter;
                $update = ['cogs' => $hppAvg];
                $itemUpdate->update($update);
            }


            $orderTarget = Porder::where('id', $record->id);
            // $lastEdit = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('date_payment');

            // Update siapa yang BUAT
            OrderItem::where('porder_id', $record->id)->update([
                'updated_by' => Auth::user()->id,
                'status' => $record->status,
                'date_order' => $record->date_order
            ]);

            // Update siapa yang JUAL/BELI di PAYMENT
            Payment::where('paymentable_id', $record->id)->where('paymentable_type', Porder::class)->update([
                'updated_by' => Auth::user()->id,
                'user_id' => $record->user_id,
            ]);

            // Tanggal Pelunasan
            if ($record->is_paid == 1) {
                $dataPAIDat = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('date_payment');
                if ($record->total_cashback >= 0) {
                    foreach ($record->payments as $payment) {
                        Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->where('id', $payment->id)
                            ->update([
                                'nominal_plus' => 0,
                                'nominal' => $payment->nominal_mins - 0,
                                'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                                'kredit' => 'NR-DB-B-1100 CASH / BANK',
                            ]);
                    }
                    $nominalMins = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('nominal_mins');
                    Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->take(1)
                        ->update([
                            'nominal_plus' => $record->total_cashback,
                            'nominal' => $nominalMins - $record->total_cashback,
                            'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'kredit' => 'NR-DB-B-1100 CASH / BANK',
                        ]);
                }
            } else {
                $dataPAIDat = null;
                foreach ($record->payments as $payment) {
                    Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', "Purchase")->where('id', $payment->id)
                        ->update([
                            'nominal_plus' => 0,
                            'nominal' => $payment->nominal_mins - 0,
                            'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'kredit' => 'NR-DB-B-1100 CASH / BANK',
                        ]);
                }
            }

            // hitung berat dan ambil nilai beli untuk jurnal
            $barang_terbeli = 0;
            foreach ($record->items as $item) {
                $dataItem = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
                $dataItem->update(['total_weight' => $item->p_quantity * $produk_weight]);

                $barang_terbeli += Product::where('id', $dataItem->product_id)->value('cogs') * $item->p_quantity;
            }
            $sum_weight = OrderItem::where('porder_id', $record->id)->sum('total_weight');

            /// update barangterbeli
            Payment::where('paymentable_type', Porder::class)
                ->where('paymentable_id', $record->id)
                ->where('mutation_type', "Barang Terbeli")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $record->date_order,
                    'nominal_plus' => $barang_terbeli,
                    'nominal_mins' => 0,
                    'nominal' => $barang_terbeli,
                ]);

            $updatePaid = [
                'paid_at' => $dataPAIDat,
                'total_weight' => $sum_weight,
                'updated_by' => Auth::user()->id,
            ];
            $orderTarget->update($updatePaid);
    }

    protected function getRedirectUrl(): string
    {
        // return $this->previousUrl;
        return $this->getResource()::getUrl('index');
    }
}
