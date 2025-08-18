<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Imports\BrandsImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('import')->view('filament.import.brand-import'),
            Actions\Action::make('export')->url(url('/exportbrands?' . $decodeQueryString)),
            Actions\CreateAction::make(),
        ];
    }

    public $file = '';

    public function save()
    {
        if ($this->file != '') {
            Excel::import(new BrandsImport, $this->file);
        }
    }
}
