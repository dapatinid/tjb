<?php

namespace App\Filament\Resources\PorderResource\Pages;

use App\Filament\Resources\PorderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPorder extends ViewRecord
{
    protected static string $resource = PorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
