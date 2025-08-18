<?php

namespace App\Filament\Resources\JumboTronResource\Pages;

use App\Filament\Resources\JumboTronResource;
use App\Models\JumboTron;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditJumboTron extends EditRecord
{
    protected static string $resource = JumboTronResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(
                function (JumboTron $record) {
                    if ($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }
                }
            ),
        ];
    }
}
