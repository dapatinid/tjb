<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StokStatusExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate, $endDate)
    {
        $this->data = collect(json_decode(json_encode($data), true));
        $this->startDate = $startDate ?: '-';
        $this->endDate = $endDate ?: '-';
    }

    public function headings(): array
    {
        // Ambil nama branch dari user login. 
        // Diberi pengecekan (fallback) untuk mencegah error jika relasi/branch kosong
        $branchName = auth()->check() && auth()->user()->branch 
            ? auth()->user()->branch->name 
            : 'Pusat'; // Ganti 'Pusat' dengan default fallback Anda

        // Menyusun string Judul Besar
        $title = sprintf(
            '%s ~ STOK STATUS %s sd %s ~ diunduh pada %s', 
            $branchName,
            $this->startDate, 
            $this->endDate, 
            now()->format('Y-m-d H:i:s')
        );

        return [
            [$title], // Baris 1: Judul Besar (A1)
            [],       // Baris 2: Kosong
            [         // Baris 3: Heading Kolom (A3)
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
            ]
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

    public function styles(Worksheet $sheet)
    {
        // 1. Merge cell A1 sampai K1 untuk judul agar rapi
        $sheet->mergeCells('A1:K1');

        // 2. Dapatkan baris terakhir yang ada datanya
        $highestRow = $sheet->getHighestRow();

        // 3. Terapkan Border dari A3 sampai K{baris_terakhir}
        // Kolom K adalah kolom ke-11 (Sld Gdg)
        $sheet->getStyle('A3:K' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Warna Hitam
                ],
            ],
        ]);

        return [
            // Style Baris 1 (Judul Besar)
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Style Baris 3 (Heading Tabel)
            3 => ['font' => ['bold' => true]],
        ];
    }
}