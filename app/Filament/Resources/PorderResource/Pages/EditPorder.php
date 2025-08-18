<?php

namespace App\Filament\Resources\PorderResource\Pages;

use App\Filament\Resources\PorderResource;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Porder;
use App\Models\Product;
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
    //     $data['updated_by'] = auth()->user()->id;
    //     return $data;
    // }

    // protected function afterSave(): void
    // {
    //     // Update siapa yang EDIT
    //     $record = $this->record;
    //     OrderItem::where('porder_id', $record->id)->update(['updated_by' => Auth::user()->id]);

    //     // Update siapa yang EDIT
    //     // $record = $this->record;
    //     OrderItem::where('porder_id', $record->id)->update(['created_by' => Auth::user()->id, 'status' => $record->status]);

    //     // Update HPP tiap produk ketika melakukan Pembelian
    //     // $record = $this->record;
    //     $dataBelanja = OrderItem::where('porder_id', $record->id)->get();
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
    //         $hppBaru = ($item->p_unit_amount == 0) ? $hppLama : $item->p_unit_amount;
    //         $hppAvg = (($hppLama * $stokBefore) + ($hppBaru * $stokAdd)) / $stokAfter;
    //         $update = ['cogs' => $hppAvg];
    //         $itemUpdate->update($update);
    //     }

    //     // Update siapa yang BELI di PAYMENT
    //     $user_id = Porder::where('id', $record->id)->value('user_id');
    //     Payment::where('paymentable_id', $record->id)->where('paymentable_type', Porder::class)->update(['user_id' => $user_id]);
    // }

    protected function getRedirectUrl(): string
    {
        // return $this->previousUrl;
        return $this->getResource()::getUrl('index');
    }
}
