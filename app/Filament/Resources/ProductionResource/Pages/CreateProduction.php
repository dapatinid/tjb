<?php

namespace App\Filament\Resources\ProductionResource\Pages;

use App\Filament\Resources\ProductionResource;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Production;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;

    protected function afterCreate(): void
    {
        // Update siapa yang BUAT
        $record = $this->record;
        OrderItem::where('production_id', $record->id)->update(['created_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);

        // hitung berat
        foreach ($record->items as $item) {
            $data = OrderItem::find($item->id);
            $produk_weight = Product::where('id', $data->product_id)->value('weight');
            $data->update(['total_weight' => ($item->p_quantity - $item->quantity) * $produk_weight]);
        }
        $sum_weight = OrderItem::where('production_id', $record->id)->sum('total_weight');
        Production::where('id', $record->id)->update(['total_weight' => $sum_weight]);

        // jurnal PRODUKSI
        $barang_produksi = $record->grand_total;
        $barangproduksi = new Payment();
        $barangproduksi->paymentable_id = $record->id;
        $barangproduksi->paymentable_type = 'App\Models\Production';
        $barangproduksi->date_payment = $record->date_order;
        $barangproduksi->currency = 'idr';
        if ($barang_produksi >= 0) {
            $barangproduksi->mutation_type = 'Barang Produksi Berkembang';
            $barangproduksi->debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
            $barangproduksi->kredit = 'LR-KR-E-9200 Barang Produksi Mengembang';
            $barangproduksi->nominal_plus = $barang_produksi;
            $barangproduksi->nominal_mins = 0;
            $barangproduksi->nominal = $barang_produksi;
        } else {
            $barangproduksi->mutation_type = 'Barang Produksi Menyusut';
            $barangproduksi->debit = 'LR-DB-F-1200 Barang Produksi Menyusut';
            $barangproduksi->kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
            $barangproduksi->nominal_plus = 0;
            $barangproduksi->nominal_mins = abs($barang_produksi);
            $barangproduksi->nominal = abs($barang_produksi);
        }
        $barangproduksi->user_id = $record->user_id;
        $barangproduksi->created_by = Auth::user()->id;
        $barangproduksi->updated_by = Auth::user()->id;
        $barangproduksi->branch_id  = Auth::user()->branch_id;
        $barangproduksi->save();
        
        $this->data = null; // untuk mereset form agar tombol tidak double click
    }
}
