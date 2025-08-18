<?php

namespace App\Filament\Resources\TrStkInResource\Pages;

use App\Filament\Resources\TrStkInResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrStkIn;
use App\Models\TrStkOut;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EditTrStkIn extends EditRecord
{
    protected static string $resource = TrStkInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }
    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $data['updated_by'] = Auth::user()->id;
    //     return $data;
    // }

    // protected function afterSave(): void
    // {
    //     // Update HPP tiap produk ketika melakukan Transfer In
    //     $record = $this->record;
    //     $dataBelanja = OrderItem::where('tr_stk_in_id', $record->id)->get();
    //     $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
    //     foreach ($dataBelanja as $item) {
    //         $boughtqty = $orderitems->where('product_id', $item->product_id)->sum('p_quantity');
    //         $soldqty = $orderitems->where('product_id', $item->product_id)->sum('quantity');
    //         $stokBefore_validate = $boughtqty - $soldqty - $item->p_quantity;
    //         if ($stokBefore_validate <= 0) {
    //             $stokBefore = 0;
    //         } else {
    //             $stokBefore = $stokBefore_validate;
    //         }
    //         $stokAdd = $item->p_quantity;
    //         $stokAfter = $stokBefore + $stokAdd;

    //         $itemUpdate = Product::where('id', $item->product_id);
    //         $hppLama = $itemUpdate->value('cogs');
    //         $hppBaru = $item->unit_amount;
    //         $hppAvg = (($hppLama * $stokBefore) + ($hppBaru * $stokAdd)) / $stokAfter;
    //         $update = ['cogs' => $hppAvg];
    //         $itemUpdate->update($update);
    //     }

    //     // Update yang Edit di ORDERITEM dan INVOICE
    //     TrStkIn::where('id', $record->id)->update(['user_id' => Auth::user()->id, 'updated_by' => Auth::user()->id]);
    //     $outID = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $record->code_tr))->value('id');
    //     OrderItem::where('tr_stk_in_id', $record->id)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);
    //     OrderItem::where('tr_stk_out_id', $outID)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);
    // }
}
