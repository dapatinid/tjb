<?php

namespace App\Filament\Resources\TrStkInResource\Pages;

use App\Filament\Resources\TrStkInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrStkIns extends ListRecords
{
    protected static string $resource = TrStkInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
