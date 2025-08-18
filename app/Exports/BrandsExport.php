<?php

namespace App\Exports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;

class BrandsExport implements FromCollection
{
    protected $bySearch;

    function __construct($bySearch)
    {
        $this->bySearch = $bySearch;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->bySearch != null) {
            return Brand::where('name', 'like', "%{$this->bySearch}%")->get();
        } else {
            return Brand::get();
        }
    }
}
