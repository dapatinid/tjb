<?php

namespace App\Filament\Resources\AdjItemResource\Pages;

use App\Filament\Resources\AdjItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdjItem extends ViewRecord
{
    protected static string $resource = AdjItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
