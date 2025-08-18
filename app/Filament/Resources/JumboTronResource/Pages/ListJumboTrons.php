<?php

namespace App\Filament\Resources\JumboTronResource\Pages;

use App\Filament\Resources\JumboTronResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJumboTrons extends ListRecords
{
    protected static string $resource = JumboTronResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
