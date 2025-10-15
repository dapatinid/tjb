<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

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
    {
        $record = $this->record;

            if ($record->isDirty('date_order')) {
                $hari = Carbon::parse($record->date_order)->format('Y-m-d');
                $antri = Order::where('branch_id', Auth::user()->branch_id)->where('date_order', 'like', "%$hari%")->count() + 1;
                Order::where('id', $record->id)->update(['q' => $antri,]);
            }
            // hitung Piutang Penjualan untuk jurnal
            // if ($record->isDirty('grand_total')) {
            Payment::where('paymentable_type', Order::class)
                ->where('paymentable_id', $record->id)
                ->where('mutation_type', "Piutang Penjualan")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $record->date_order,
                    'nominal_plus' => $record->grand_total,
                    'nominal_mins' => 0,
                    'nominal' => $record->grand_total,
                ]);
            // }        
    }

    protected function afterSave(): void
    {
        $record = $this->record;

            $orderTarget = Order::where('id', $record->id);
            $user_id = Order::where('id', $record->id)->value('user_id');
            // $lastEdit = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('date_payment');

            // Update siapa yang BUAT
            OrderItem::where('order_id', $record->id)->update([
                'updated_by' => Auth::user()->id,
                'status' => $record->status,
                'date_order' => $record->date_order
            ]);

            // Update siapa yang JUAL/BELI di PAYMENT
            Payment::where('paymentable_id', $record->id)->where('paymentable_type', Order::class)->update([
                'updated_by' => Auth::user()->id,
                'user_id' => $user_id,
            ]);

            // Tanggal Pelunasan
            if ($record->is_paid == 1) {
                $dataPAIDat = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('date_payment');
                if ($record->total_cashback >= 0) {
                    foreach ($record->payments as $payment) {
                        Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->where('id', $payment->id)
                            ->update([
                                'nominal_mins' => 0,
                                'nominal' => $payment->nominal_plus - 0,
                                'debit' => 'NR-DB-B-1100 CASH / BANK',
                                'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                            ]);
                    }
                    $nominalPlus = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('nominal_plus');
                    Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->take(1)
                        ->update([
                            'nominal_mins' => $record->total_cashback,
                            'nominal' => $nominalPlus - $record->total_cashback,
                            'debit' => 'NR-DB-B-1100 CASH / BANK',
                            'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                        ]);
                }
            } else {
                $dataPAIDat = null;
                foreach ($record->payments as $payment) {
                    Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', "Sales")->where('id', $payment->id)
                        ->update([
                            'nominal_mins' => 0,
                            'nominal' => $payment->nominal_plus - 0,
                            'debit' => 'NR-DB-B-1100 CASH / BANK',
                            'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                        ]);
                }
            }

            // hitung berat dan ambil nilai beli untuk jurnal
            $barang_terjual = 0;
            foreach ($record->items as $item) {
                $dataItem = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
                $dataItem->update(['total_weight' => $item->quantity * $produk_weight]);

                $barang_terjual += Product::where('id', $dataItem->product_id)->value('cogs') * $item->quantity;
            }
            $sum_weight = OrderItem::where('order_id', $record->id)->sum('total_weight');

            /// update barangterjual
            Payment::where('paymentable_type', Order::class)
                ->where('paymentable_id', $record->id)
                ->where('mutation_type', "Barang Terjual")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $record->date_order,
                    'nominal_plus' => 0,
                    'nominal_mins' => $barang_terjual,
                    'nominal' => $barang_terjual,
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
