<?php

namespace App\Filament\Resources\TrStkOutResource\Pages;

use App\Filament\Resources\TrStkOutResource;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTrStkOut extends CreateRecord
{
    protected static string $resource = TrStkOutResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        $tfIN = new TrStkIn();
        $tfIN->branch_id = $record->to_branch_id;
        $tfIN->user_id = $record->user_id;
        $tfIN->created_by = $record->created_by;
        $tfIN->updated_by = $record->updated_by;
        $tfIN->code_tr = Str::replace('TRO', 'TRI', $record->code_tr);
        $tfIN->from_branch_id = $record->from_branch_id;
        $tfIN->to_branch_id = $record->to_branch_id;
        $tfIN->date_order = $record->date_order;
        // $tfIN->user_id = $record->user_id;
        $tfIN->currency = $record->currency;
        $tfIN->status = $record->status;
        $tfIN->notes = $record->notes;
        // $tfIN->grand_total = $record->grand_total;
        $tfIN->created_at = $record->created_at;
        $tfIN->updated_at = $record->updated_at;

        $tfIN->save();

        // CabangPenerima START
        $ItemFrom = $record->items;
        foreach ($ItemFrom as $item) {
            $produkdari = Product::where('branch_id', $record->from_branch_id);
            $produk = Product::where('branch_id', $record->to_branch_id);
            $sku = $produkdari->where('id', $item['product_id'])->value('sku');
            $produkID = $produk->where('sku', $sku)->value('id');
            $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
            $boughtqty = $orderitems->where('product_id', $produkID)->sum('p_quantity');
            $soldqty = $orderitems->where('product_id', $produkID)->sum('quantity');
            $stokBef = $boughtqty - $soldqty;
            $stokAft = $boughtqty - $soldqty + $item['quantity'];
            $tfIN->items()->saveMany([
                new OrderItem([
                    'product_id' => $produkID,
                    'stock_before' => $stokBef,
                    'stock_after' => $stokAft,
                    'unit_name' => $produk->where('sku', $sku)->value('unit_name'),
                    'total_weight' => $produk->where('sku', $sku)->value('weight') * $item['quantity'],
                    'contain' => $produk->where('sku', $sku)->value('contain'),
                    'branch_id' => $produk->where('sku', $sku)->value('branch_id'),
                    'p_quantity' => $item['quantity'],
                    'unit_amount' => $item['p_unit_amount'],
                    'total_amount' => $item['p_total_amount'],
                    'notes' => $item['notes'],
                    'mutation_type' => 'Transfer In',
                    'created_by' => Auth::user()->id,
                ]),
            ]);
        }

        // Update siapa yang BUAT
        $record = $this->record;
        OrderItem::where('tr_stk_out_id', $record->id)->update(['created_by' => Auth::user()->id, 'updated_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);
        $inID = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $record->code_tr))->value('id');
        OrderItem::where('tr_stk_in_id', $inID)->update(['created_by' => Auth::user()->id, 'status' => $record->status, 'date_order' => $record->date_order]);

        // hitung berat
        foreach ($record->items as $item) {
            $data = OrderItem::find($item->id);
            $produk_weight = Product::where('id', $data->product_id)->value('weight');
            $data->update(['total_weight' => $item->quantity * $produk_weight]);
        }
        $sum_weight = OrderItem::where('tr_stk_out_id', $record->id)->sum('total_weight');
        TrStkOut::where('id', $record->id)->update(['total_weight' => $sum_weight]);

        // hitung berat cabang penerima
        // $sum_weight = OrderItem::where('tr_stk_in_id', $record->id)->sum('total_weight');
        // TrStkIn::where('id', $record->id)->update(['total_weight' => $sum_weight]);

        // jurnal TRANSFER
        $barang_transfer = $record->grand_total;
        $barangtransferOut = new Payment();
        $barangtransferOut->paymentable_id = $record->id;
        $barangtransferOut->paymentable_type = 'App\Models\TrStkOut';
        $barangtransferOut->date_payment = $record->date_order;
        $barangtransferOut->currency = 'idr';
        $barangtransferOut->mutation_type = 'Barang Transfer Keluar';
        $barangtransferOut->debit = 'NR-KR-D-2500 Barang Transfer';
        $barangtransferOut->kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
        $barangtransferOut->nominal_plus = 0;
        $barangtransferOut->nominal_mins = $barang_transfer;
        $barangtransferOut->nominal = $barang_transfer;
        $barangtransferOut->user_id = $record->user_id;
        $barangtransferOut->created_by = auth()->user()->id;
        $barangtransferOut->updated_by = auth()->user()->id;
        $barangtransferOut->branch_id  = auth()->user()->branch_id;
        $barangtransferOut->save();

        $this->data = null; // untuk mereset form agar tombol tidak double click
    }
}
