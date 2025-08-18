<?php

namespace App\Filament\Resources\PorderResource\Pages;

use App\Filament\Resources\PorderResource;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Porder;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePorder extends CreateRecord
{
    protected static string $resource = PorderResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $today = Carbon::today()->format('Y-m-d');
    //     $data['q'] = PPorder::where('branch_id', auth()->user()->branch_id)->where('date_order', 'like', "%$today%")->count() + 1;
    //     return $data;
    // }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Update HPP tiap produk ketika melakukan Pembelian
        $dataBelanja = OrderItem::where('porder_id', $record->id)->get();
        $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
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

        $hari = Carbon::parse($record->date_order)->format('Y-m-d');
        $antri = Porder::where('branch_id', auth()->user()->branch_id)->where('date_order', 'like', "%$hari%")->count();
        $user_id = Porder::where('id', $record->id)->value('user_id');

        // Update siapa yang BUAT
        OrderItem::where('porder_id', $record->id)->update([
            'created_by' => auth()->user()->id,
            'status' => $record->status,
            'date_order' => $record->date_order
        ]);

        // Update siapa yang BELI di PAYMENT
        Payment::where('paymentable_id', $record->id)->where('paymentable_type', Porder::class)->update([
            'created_by' => auth()->user()->id,
            'user_id' => $user_id,
            'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
            'kredit' => 'NR-DB-B-1100 CASH / BANK',
            'rekening' => 'KAS KASIR',
        ]);

        // Tanggal Pelunasan
        $dataOrder = Porder::where('id', $record->id);
        if ($record->is_paid == 1) {
            $dataPAIDat = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Purchase')->orderBy('date_payment', 'desc')->value('date_payment');
            if ($record->total_cashback >= 0) {
                foreach ($record->payments as $payment) {
                    Payment::where('paymentable_type', Porder::class)
                        ->where('paymentable_id', $record->id)
                        ->where('mutation_type', 'Purchase')
                        ->where('id', $payment->id)
                        ->update([
                            'nominal_plus' => 0,
                            'nominal' => $payment->nominal_mins - 0,
                        ]);
                }
                $nominalMins = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Purchase')->orderBy('date_payment', 'desc')->value('nominal_mins');
                Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Purchase')->orderBy('date_payment', 'desc')->take(1)
                    ->update([
                        'nominal_plus' => $record->total_cashback,
                        'nominal' => $nominalMins - $record->total_cashback,
                    ]);
            }
        } else {
            $dataPAIDat = null;
            foreach ($record->payments as $payment) {
                Payment::where('paymentable_type', Porder::class)
                    ->where('paymentable_id', $record->id)
                    ->where('mutation_type', 'Purchase')
                    ->where('id', $payment->id)
                    ->update([
                        'nominal_plus' => 0,
                        'nominal' => $payment->nominal_mins - 0,
                    ]);
            }
        }

        // hitung Hutang Pembelian untuk jurnal
        $hutang = new Payment();
        $hutang->paymentable_id = $record->id;
        $hutang->paymentable_type = 'App\Models\Porder';
        $hutang->mutation_type = 'Hutang Pembelian';
        $hutang->date_payment = $record->date_order;
        $hutang->currency = 'idr';
        $hutang->debit = 'LR-DB-F-2100 Blj Persediaan Barang Dagang';
        $hutang->kredit = 'NR-KR-C-2000 Hutang_Pembelian_Barang';

        $hutang->nominal_plus = 0;
        $hutang->nominal_mins = $record->grand_total;
        $hutang->nominal = $record->grand_total;

        $hutang->user_id = $record->user_id;
        $hutang->created_by = Auth::user()->id;
        $hutang->updated_by = Auth::user()->id;
        $hutang->branch_id  = Auth::user()->branch_id;
        $hutang->save();

        // hitung berat dan ambil nilai beli untuk jurnal
        $barang_terbeli = 0;
        foreach ($record->items as $item) {
            $dataItem = OrderItem::find($item->id);
            $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
            $dataItem->update(['total_weight' => $item->p_quantity * $produk_weight]);

            $barang_terbeli += Product::where('id', $dataItem->product_id)->value('cogs') * $item->p_quantity;
        }
        $sum_weight = OrderItem::where('porder_id', $record->id)->sum('total_weight');

        $barangterjual = new Payment();
        $barangterjual->paymentable_id = $record->id;
        $barangterjual->paymentable_type = 'App\Models\Porder';
        $barangterjual->mutation_type = 'Barang Terbeli';
        $barangterjual->date_payment = $record->date_order;
        $barangterjual->currency = 'idr';
        $barangterjual->debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
        $barangterjual->kredit = 'LR-KR-E-9100 Barang Terbeli';
        $barangterjual->nominal_plus = $barang_terbeli;
        $barangterjual->nominal_mins = 0;
        $barangterjual->nominal = $barang_terbeli;
        $barangterjual->user_id = $record->user_id;
        $barangterjual->created_by = Auth::user()->id;
        $barangterjual->updated_by = Auth::user()->id;
        $barangterjual->branch_id  = Auth::user()->branch_id;
        $barangterjual->save();

        $updatePaid = [
            'q' => $antri,
            'paid_at' => $dataPAIDat,
            'total_weight' => $sum_weight,
            'created_by' => auth()->user()->id,
        ];
        $dataOrder->update($updatePaid);
    }
}
