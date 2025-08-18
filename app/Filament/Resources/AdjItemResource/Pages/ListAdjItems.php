<?php

namespace App\Filament\Resources\AdjItemResource\Pages;

use App\Filament\Resources\AdjItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdjItems extends ListRecords
{
    protected static string $resource = AdjItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
