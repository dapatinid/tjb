<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
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
    //     $data['updated_by'] = auth()->user()->id;
    //     return $data;
    // }

    // protected function afterSave(): void
    // {
    //     // Update siapa yang EDIT
    //     $record = $this->record;
    //     OrderItem::where('order_id', $record->id)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);

    //     // Update siapa yang BELI di PAYMENT
    //     $user_id = Order::where('id', $record->id)->value('user_id');
    //     Payment::where('paymentable_id', $record->id)->where('paymentable_type', Order::class)->update(['user_id' => $user_id]);
    // }

    protected function getRedirectUrl(): string
    {
        // return $this->previousUrl;
        return $this->getResource()::getUrl('index');
    }
}
