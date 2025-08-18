<?php

namespace App\Filament\Resources\TrStkOutResource\Pages;

use App\Filament\Resources\TrStkOutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrStkOuts extends ListRecords
{
    protected static string $resource = TrStkOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
