<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }

    // public function getTabs(): array
    // {
    //     return [
    //         null => Tab::make('Ledger')->icon('heroicon-m-book-open'),
    //         // 'laba-rugi' => Tab::make()->query(fn($query) => $query->where('debit', 'like', '%LR-%')->orwhere('kredit', 'like', '%LR-%'))->icon('heroicon-m-chart-bar'),
    //         'rekening' => Tab::make()->query(fn($query) => $query->whereNull('deleted_at')->where('debit', 'NR-DB-B-1100 CASH / BANK')->orwhere('kredit', 'NR-DB-B-1100 CASH / BANK'))->icon('heroicon-m-credit-card'),
    //     ];
    // }
}
