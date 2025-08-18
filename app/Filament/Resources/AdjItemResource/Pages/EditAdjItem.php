<?php

namespace App\Filament\Resources\AdjItemResource\Pages;

use App\Filament\Resources\AdjItemResource;
use App\Models\OrderItem;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditAdjItem extends EditRecord
{
    protected static string $resource = AdjItemResource::class;

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
    //     OrderItem::where('adj_item_id', $record->id)->update(['updated_by' => Auth::user()->id, 'status' => $record->status]);
    // }
}
