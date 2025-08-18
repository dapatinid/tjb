<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Imports\ProductsImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        $data = [
            'exportbutton' => Actions\Action::make('export')->url(url('/exportproducts?' . $decodeQueryString)),
            'createbutton' => Actions\CreateAction::make()->url(url('/admin/products/create')),
            // 'createbutton' => Actions\CreateAction::make(), # with Modal
        ];

        return view('filament.import.product-import', $data);
    }

    public $file = '';

    public function save()
    {
        if ($this->file != '') {
            Excel::import(new ProductsImport, $this->file);
        }
    }
}
