<?php

namespace App\Http\Controllers;

use App\Exports\BrandsExport;
use App\Exports\ProductsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportProduct()
    {
        // $active = request('tableFilters.is_active.isActive');
        $category = request('tableFilters.category.value');
        // $brand = request('tableFilters.brand.value');

        return Excel::download(new ProductsExport($category), 'products.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        // return "Hello Export : $active.$category.$brand";
    }
    public function exportBrand()
    {
        $bySearch = request('tableSearch');

        return Excel::download(new BrandsExport($bySearch), 'brands.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        // return "Hello Export : $bySearch";
    }
}
