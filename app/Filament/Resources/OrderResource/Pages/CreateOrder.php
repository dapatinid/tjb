<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $today = Carbon::today()->format('Y-m-d');
    //     $data['q'] = Order::where('branch_id', auth()->user()->branch_id)->where('date_order', 'like', "%$today%")->count() + 1;
    //     return $data;
    // }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $hari = Carbon::parse($record->date_order)->format('Y-m-d');
        $antri = Order::where('branch_id', auth()->user()->branch_id)->where('date_order', 'like', "%$hari%")->count();
        $user_id = Order::where('id', $record->id)->value('user_id');

        // Update siapa yang BUAT
        OrderItem::where('order_id', $record->id)->update([
            'created_by' => auth()->user()->id,
            'status' => $record->status,
            'date_order' => $record->date_order
        ]);

        // Update siapa yang BELI di PAYMENT
        Payment::where('paymentable_id', $record->id)->where('paymentable_type', Order::class)->update([
            'created_by' => auth()->user()->id,
            'user_id' => $user_id,
            'debit' => 'NR-DB-B-1100 CASH / BANK',
            'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
            // 'rekening' => 'KAS KASIR',
        ]);

        // Tanggal Pelunasan
        $dataOrder = Order::where('id', $record->id);
        if ($record->is_paid == 1) {
            $dataPAIDat = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Sales')->orderBy('date_payment', 'desc')->value('date_payment');
            if ($record->total_cashback >= 0) {
                foreach ($record->payments as $payment) {
                    Payment::where('paymentable_type', Order::class)
                        ->where('paymentable_id', $record->id)
                        ->where('mutation_type', 'Sales')
                        ->where('id', $payment->id)
                        ->update([
                            'nominal_mins' => 0,
                            'nominal' => $payment->nominal_plus - 0,
                        ]);
                }
                $nominalPlus = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Sales')->orderBy('date_payment', 'desc')->value('nominal_plus');
                Payment::where('paymentable_type', Order::class)->where('paymentable_id', $record->id)->where('mutation_type', 'Sales')->orderBy('date_payment', 'desc')->take(1)
                    ->update([
                        'nominal_mins' => $record->total_cashback,
                        'nominal' => $nominalPlus - $record->total_cashback,
                    ]);
            }
        } else {
            $dataPAIDat = null;
            foreach ($record->payments as $payment) {
                Payment::where('paymentable_type', Order::class)
                    ->where('paymentable_id', $record->id)
                    ->where('mutation_type', 'Sales')
                    ->where('id', $payment->id)
                    ->update([
                        'nominal_mins' => 0,
                        'nominal' => $payment->nominal_plus - 0,
                    ]);
            }
        }

        // hitung Piutang Penjualan untuk jurnal
        $piutang = new Payment();
        $piutang->paymentable_id = $record->id;
        $piutang->paymentable_type = 'App\Models\Order';
        $piutang->mutation_type = 'Piutang Penjualan';
        $piutang->date_payment = $record->date_order;
        $piutang->currency = 'idr';
        $piutang->debit = 'NR-DB-B-3000 Piutang Penjualan Barang';
        $piutang->kredit = 'LR-KR-E-1000 Pendapatan Penjualan Kasir';

        $piutang->nominal_plus = $record->grand_total;
        $piutang->nominal_mins = 0;
        $piutang->nominal = $record->grand_total;

        $piutang->user_id = $record->user_id;
        $piutang->created_by = Auth::user()->id;
        $piutang->updated_by = Auth::user()->id;
        $piutang->branch_id  = Auth::user()->branch_id;
        $piutang->save();

        // hitung berat dan ambil nilai beli untuk jurnal
        $barang_terjual = 0;
        foreach ($record->items as $item) {
            $dataItem = OrderItem::find($item->id);
            $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
            $dataItem->update(['total_weight' => $item->quantity * $produk_weight]);

            $barang_terjual += Product::where('id', $dataItem->product_id)->value('cogs') * $item->quantity;
        }
        $sum_weight = OrderItem::where('order_id', $record->id)->sum('total_weight');

        $barangterjual = new Payment();
        $barangterjual->paymentable_id = $record->id;
        $barangterjual->paymentable_type = 'App\Models\Order';
        $barangterjual->mutation_type = 'Barang Terjual';
        $barangterjual->date_payment = $record->date_order;
        $barangterjual->currency = 'idr';
        $barangterjual->debit = 'LR-DB-F-1100 Barang Terjual';
        $barangterjual->kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
        $barangterjual->nominal_plus = 0;
        $barangterjual->nominal_mins = $barang_terjual;
        $barangterjual->nominal = $barang_terjual;
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

        $this->data = null; // untuk mereset form agar tombol tidak double click
    }
}
