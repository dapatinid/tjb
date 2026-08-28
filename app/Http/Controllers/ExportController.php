<?php

namespace App\Http\Controllers;

use App\Exports\BrandsExport;
use App\Exports\ProductsExport;
use App\Exports\DompetExportByDateTrans;
use App\Exports\DompetExportByDateDibuat;
use App\Exports\DompetExportByDateDiedit;
use App\Exports\StokStatusExport;
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
    public function exportDompetDateTrans($byfilter)
    {
        // return "Hello Export : " . $byfilter;
        return Excel::download(new DompetExportByDateTrans($byfilter), 'dompet-bydate-Transaksi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function exportDompetDateDibuat($byfilter)
    {
        // return "Hello Export : " . $byfilter;
        return Excel::download(new DompetExportByDateDibuat($byfilter), 'dompet-bydate-Dibuat.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function exportDompetDateDiedit($byfilter)
    {
        // return "Hello Export : " . $byfilter;
        return Excel::download(new DompetExportByDateDiedit($byfilter), 'dompet-bydate-Diedit.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportTabelStokStatus(Request $request)
    {
        $data = $request->input('data', []);
        
        // Tangkap tanggal awal dan akhir dari JSON Request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filename = 'stok_status_' . now()->format('Ymd_His') . auth()->user()->branch->name . '.xlsx';
        
        // Lempar parameter tanggal ke Constructor StokStatusExport
        return Excel::download(new StokStatusExport($data, $startDate, $endDate), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
