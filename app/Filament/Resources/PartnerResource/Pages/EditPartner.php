<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Models\Branch;
use App\Models\Partner;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make()->after(
                function (Partner $record) {
                    if ($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }
                }
            ),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['updated_by'] = auth()->user()->id;
        return $data;
    }

    protected function afterSave(): void
    {
        $partner = $this->record;
        $PartnerID = $partner->id;
        $PartnerNAME = $partner->name;
        $databranch = Branch::where('partner_id', $PartnerID);
        $updateBranch = [
            'name_partner' => $PartnerNAME,
        ];
        $databranch->update($updateBranch);
    }
}
