<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use App\Models\Branch;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditBranch extends EditRecord
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(
                function (Branch $record) {
                    if ($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }
                    if ($record->logo) {
                        Storage::disk('public')->delete($record->logo);
                    }
                }
            ),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['updated_by'] = auth()->user()->id;
        $data['partner_id'] = auth()->user()->partner_id;
        return $data;
    }
}
