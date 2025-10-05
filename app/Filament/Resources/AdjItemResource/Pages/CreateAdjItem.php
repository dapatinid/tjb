<?php

namespace App\Filament\Resources\AdjItemResource\Pages;

use App\Filament\Resources\AdjItemResource;
use App\Models\AdjItem;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAdjItem extends CreateRecord
{
    protected static string $resource = AdjItemResource::class;

    protected function afterCreate(): void
    {
        // Update siapa yang BUAT
        $record = $this->record;
        OrderItem::where('adj_item_id', $record->id)->update(['created_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);

        // hitung berat
        foreach ($record->items as $item) {
            $data = OrderItem::find($item->id);
            $produk_weight = Product::where('id', $data->product_id)->value('weight');
            $data->update(['total_weight' => ($item->p_quantity - $item->quantity) * $produk_weight]);
        }
        $sum_weight = OrderItem::where('adj_item_id', $record->id)->sum('total_weight');
        AdjItem::where('id', $record->id)->update(['total_weight' => $sum_weight]);

        // jurnal STOK OPNAME
        $barang_stok_opname = $record->grand_total;
        $barangsesuaikan = new Payment();
        $barangsesuaikan->paymentable_id = $record->id;
        $barangsesuaikan->paymentable_type = 'App\Models\AdjItem';
        $barangsesuaikan->date_payment = $record->date_order;
        $barangsesuaikan->currency = 'idr';
        if ($barang_stok_opname >= 0) {
            $barangsesuaikan->mutation_type = 'Barang Stok Bertambah';
            $barangsesuaikan->debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
            $barangsesuaikan->kredit = 'LR-KR-E-9300 Barang Stok Bertambah';
            $barangsesuaikan->nominal_plus = $barang_stok_opname;
            $barangsesuaikan->nominal_mins = 0;
            $barangsesuaikan->nominal = $barang_stok_opname;
        } else {
            $barangsesuaikan->mutation_type = 'Barang Stok Berkurang';
            $barangsesuaikan->debit = 'LR-DB-F-1300 Barang Stok Berkurang';
            $barangsesuaikan->kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
            $barangsesuaikan->nominal_plus = 0;
            $barangsesuaikan->nominal_mins = abs($barang_stok_opname);
            $barangsesuaikan->nominal = abs($barang_stok_opname);
        }
        $barangsesuaikan->user_id = $record->user_id;
        $barangsesuaikan->created_by = Auth::user()->id;
        $barangsesuaikan->updated_by = Auth::user()->id;
        $barangsesuaikan->branch_id  = Auth::user()->branch_id;
        $barangsesuaikan->save();

        $this->data = null; // untuk mereset form agar tombol tidak double click
    }
}
