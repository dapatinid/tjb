<?php

namespace App\Filament\Resources\PorderResource\Pages;

use App\Filament\Resources\PorderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPorders extends ListRecords
{
    protected static string $resource = PorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn($query) => $query->where('status', 'new'))->icon('heroicon-m-sparkles'),
            'processing' => Tab::make()->query(fn($query) => $query->where('status', 'processing'))->icon('heroicon-m-arrow-path'),
            'shipped' => Tab::make()->query(fn($query) => $query->where('status', 'shipped'))->icon('heroicon-m-truck'),
            'delivered' => Tab::make()->query(fn($query) => $query->where('status', 'delivered'))->icon('heroicon-m-check-badge'),
            'canceled' => Tab::make()->query(fn($query) => $query->where('status', 'canceled'))->icon('heroicon-m-x-circle'),
            'aec' => Tab::make()->query(fn($query) => $query->where('status', '!=', 'canceled')),
        ];
    }
}
