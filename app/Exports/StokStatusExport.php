<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokStatusExport implements FromCollection, WithMapping, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Pastikan data jadi array associative, bukan object JS
        $this->data = collect(json_decode(json_encode($data), true));

        // Optional debug ke log
        // \Log::info('📦 Data diterima di export:', [
        //     'count' => $this->data->count(),
        //     'sample' => $this->data->take(1),
        // ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Produk',
            'Status',
            'Beli',
            'Jual',
            'Prod',
            'Adj',
            'Tf Out',
            'Tf In',
            'Saldo',
            'Sld Gdg',
        ];
    }

    public function map($item): array
    {
        return [
            $item['product']['id'] ?? '',
            (($item['product']['name'] ?? '') . ' ' . ($item['product']['variant'] ?? '')),
            (($item['saldo'] ?? 0) - ($item['product']['low_alert'] ?? 0)) >= 0 ? 'aman' : 'LOW',
            $item['beli'] ?? 0,
            -($item['jual'] ?? 0),
            ($item['ProdPlus'] ?? 0) - ($item['ProdMins'] ?? 0),
            ($item['AdjPlus'] ?? 0) - ($item['AdjMins'] ?? 0),
            -($item['TfOut'] ?? 0),
            $item['TfIn'] ?? 0,
            $item['saldo'] ?? 0,
            $item['saldoGudang'] ?? 0,
        ];
    }

    public function collection()
    {
        return $this->data;
    }
}
