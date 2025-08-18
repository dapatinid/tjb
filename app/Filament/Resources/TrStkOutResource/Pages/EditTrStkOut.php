<?php

namespace App\Filament\Resources\TrStkOutResource\Pages;

use App\Filament\Resources\TrStkOutResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EditTrStkOut extends EditRecord
{
    protected static string $resource = TrStkOutResource::class;

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
    //     $data['updated_by'] = auth()->user()->id;
    //     return $data;
    // }

    // protected function beforeSave(): void
    // {
    //     $record = $this->record;
    //     $tr_stk_in_id = Str::replace('TRO', 'TRI', $record->code_tr);
    //     TrStkIn::where('code_tr', $tr_stk_in_id)->forceDelete();
    // }

    // protected function afterSave(): void
    // {
    //     $record = $this->record;
    //     TrStkOut::where('code_tr', $record->code_tr)->update(['status' => 'new']);

    //     $tfIN = new TrStkIn();
    //     $tfIN->branch_id = $record->to_branch_id;
    //     $tfIN->user_id = $record->user_id;
    //     $tfIN->created_by = $record->created_by;
    //     $tfIN->updated_by = $record->updated_by;
    //     $tfIN->code_tr = Str::replace('TRO', 'TRI', $record->code_tr);
    //     $tfIN->from_branch_id = $record->from_branch_id;
    //     $tfIN->to_branch_id = $record->to_branch_id;
    //     $tfIN->date_order = $record->date_order;
    //     // $tfIN->user_id = $record->user_id;
    //     $tfIN->currency = $record->currency;
    //     $tfIN->status = 'new';
    //     $tfIN->notes = $record->notes;
    //     // $tfIN->grand_total = $record->grand_total;
    //     $tfIN->created_at = $record->created_at;
    //     $tfIN->updated_at = $record->updated_at;

    //     $tfIN->save();

    //     // CabangPenerima START
    //     $ItemFrom = $record->items;
    //     foreach ($ItemFrom as $item) {
    //         $produkdari = Product::where('branch_id', $record->from_branch_id);
    //         $produk = Product::where('branch_id', $record->to_branch_id);
    //         $sku = $produkdari->where('id', $item['product_id'])->value('sku');
    //         $produkID = $produk->where('sku', $sku)->value('id');
    //         $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
    //         $boughtqty = $orderitems->where('product_id', $produkID)->sum('p_quantity');
    //         $soldqty = $orderitems->where('product_id', $produkID)->sum('quantity');
    //         $stokBef = $boughtqty - $soldqty;
    //         $stokAft = $boughtqty - $soldqty + $item['quantity'];
    //         $tfIN->items()->saveMany([
    //             new OrderItem([
    //                 'product_id' => $produkID,
    //                 'stock_before' => $stokBef,
    //                 'stock_after' => $stokAft,
    //                 'unit_name' => $produk->where('sku', $sku)->value('unit_name'),
    //                 'contain' => $produk->where('sku', $sku)->value('contain'),
    //                 'branch_id' => $produk->where('sku', $sku)->value('branch_id'),
    //                 'p_quantity' => $item['quantity'],
    //                 'unit_amount' => $item['p_unit_amount'],
    //                 'total_amount' => $item['p_total_amount'],
    //                 'notes' => $item['notes'],
    //                 'mutation_type' => 'Transfer In',
    //                 'created_by' => Auth::user()->id,
    //             ]),
    //         ]);
    //     }

    //     // Update siapa yang EDIT
    //     OrderItem::where('tr_stk_out_id', $record->id)->update(['created_by' => Auth::user()->id, 'updated_by' => Auth::user()->id, 'status' => $record->status]);
    //     $inID = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $record->code_tr))->value('id');
    //     OrderItem::where('tr_stk_in_id', $inID)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);

    //     $tr_stk_out_id = $record->id;
    //     OrderItem::where('tr_stk_out_id', $tr_stk_out_id)->onlyTrashed()->forceDelete();
    // }
}
