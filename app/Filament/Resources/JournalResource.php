<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Filament\Resources\JournalResource\RelationManagers;
use App\Models\Branch;
use App\Models\Journal;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $modelLabel = 'journal / transaction';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tr. Information')->schema([
                    Hidden::make('branch_id')
                        ->default(fn() => Auth::user()->branch_id)
                        ->required(),
                    Hidden::make('created_by')
                        ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                        // ->disabledOn('edit')
                        ->required(),
                    Hidden::make('updated_by')
                        ->default(fn() => Auth::user()->id) ## keperluan untuk memilih user setelah ada auth
                        ->required(),

                    DateTimePicker::make('date_journal')
                        ->label('Date')
                        ->default(now())
                        ->required()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                    Select::make('currency')
                        ->disabled(function (Get $get) {
                            if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                                return true;
                            }
                        })
                        ->default('idr')
                        ->required()
                        ->options([
                            'idr' => 'IDR',
                            'usd' => 'USD',
                            'eur' => 'EUR'
                        ])
                        ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                    Textarea::make('notes')
                        ->required()
                        ->autosize()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),

                    ToggleButtons::make('journal_type')
                        ->inline()
                        ->extraAttributes([
                            'class' => 'flex justify-center', // Centers the toggle horizontally
                        ])
                        ->default(NULL)
                        ->required()
                        ->live()
                        ->options(
                            function () {
                                if (Auth::user()->level === 'backofficer' || Auth::user()->roles[0]->name !== 'Kasir') {
                                    return [
                                        'in' => 'IN',
                                        'tf' => 'TF',
                                        'out' => 'OUT',
                                        'ps' => 'PS',
                                        'sa' => 'SA',
                                    ];
                                } else {
                                    return [
                                        'in' => 'IN',
                                        'tf' => 'TF',
                                        'out' => 'OUT',
                                    ];
                                }
                            }
                        )
                        ->colors([
                            'in' => 'success',
                            'out' => 'warning',
                            'tf' => 'info',
                            'ps' => 'danger',
                            'sa' => 'gray',
                        ])
                        ->icons([
                            'in' => 'heroicon-m-arrow-down',
                            'out' => 'heroicon-m-arrow-up',
                            'tf' => 'heroicon-m-arrows-right-left',
                            'ps' => 'heroicon-m-pause',
                            'sa' => 'heroicon-m-power',
                        ])
                        ->afterStateUpdated(function ($state, Set $set) {
                            $set('mutation_type', null);
                            $set('payment_method', null);
                            $set('debit', null);
                            $set('kredit', null);
                            $set('to_rekening', null);
                            if ($state === 'tf') {
                                $set('debit', 'NR-DB-B-1100 CASH / BANK');
                                $set('kredit', 'NR-DB-B-1100 CASH / BANK');
                            }
                        })
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                    Select::make('mutation_type')
                        ->searchable()
                        ->options(fn(Get $get): array => match ($get('journal_type')) {
                            'in' => [
                                'pendapatan di luar kasir' => 'pendapatan di luar kasir',
                                'pembayaran piutang usaha dari mitra' => 'pembayaran piutang usaha dari mitra',
                                'pembayaran piutang dari karyawan' => 'pembayaran piutang dari karyawan',
                                'tambah modal' => 'tambah modal',
                                'tarik investasi' => 'tarik investasi',
                                'kembalikan prive' => 'kembalikan prive',

                                'tambah hutang nominal' => 'tambah hutang nominal',
                            ],
                            'out' => [
                                'blj packaging' => 'blj packaging',
                                'blj utilitas produksi' => 'blj utilitas produksi',
                                'biaya administrasi dan alat kantor' => 'biaya administrasi dan alat kantor',
                                'biaya tenaga kerja' => 'biaya tenaga kerja',
                                'biaya tunjangan' => 'biaya tunjangan',
                                'biaya transportasi' => 'biaya transportasi',
                                'biaya konsumsi' => 'biaya konsumsi',
                                'biaya advertising' => 'biaya advertising',
                                'biaya telepon dan pulsa' => 'biaya telepon dan pulsa',
                                'biaya listrik dan air' => 'biaya listrik dan air',
                                'biaya kebersihan dan keamanan' => 'biaya kebersihan dan keamanan',
                                'biaya sewa atau langganan' => 'biaya sewa atau langganan',
                                'biaya instalasi perawatan dan perbaikan' => 'biaya instalasi perawatan dan perbaikan',
                                'beban overhead kantor' => 'beban overhead kantor',
                                'beban overhead lapangan' => 'beban overhead lapangan',
                                'beban bencana' => 'beban bencana',
                                'beban kesalahan kerja' => 'beban kesalahan kerja',
                                'pajak ppn' => 'pajak ppn',

                                'berikan piutang usaha ke mitra' => 'berikan piutang usaha ke mitra',
                                'berikan piutang ke karyawan' => 'berikan piutang ke karyawan',
                                'kembalikan modal' => 'kembalikan modal',
                                'ajukan investasi' => 'ajukan investasi',
                                'tarik prive' => 'tarik prive',
                                'bagikan dividen tunai' => 'bagikan dividen tunai',
                                'sedekahkan' => 'sedekahkan',

                                'bayar hutang nominal' => 'bayar hutang nominal',
                                'bayar hutang usaha' => 'bayar hutang usaha',
                                'bayar hutang operasional' => 'bayar hutang operasional',
                                'bayar hutang aset tetap' => 'bayar hutang aset tetap',
                                'bayar hutang pajak' => 'bayar hutang pajak',
                            ],
                            default => [],
                        })
                        // ->default(null)
                        ->required(fn(Get $get) => $get('journal_type') === 'in' || $get('journal_type') === 'out')
                        ->disabled(fn(Get $get) => $get('journal_type') === 'tf' || $get('journal_type') === 'ps' || $get('journal_type') === 'sa')
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state === "pendapatan di luar kasir") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "LR-KR-E-2000 Pendapatan Penjualan di luar Kasir");
                            } elseif ($state === "pembayaran piutang usaha dari mitra") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-DB-B-4000 Piutang Usaha");
                            } elseif ($state === "pembayaran piutang dari karyawan") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-DB-B-5000 Piutang Karyawan");
                            } elseif ($state === "tambah modal") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-KR-D-1000 Modal");
                            } elseif ($state === "tarik investasi") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-KR-D-2000 Investasi / Ekspansi Perusahaan");
                            } elseif ($state === "kembalikan prive") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-KR-D-3000 Prive");
                            } elseif ($state === "tambah hutang nominal") {
                                $set("debit", "NR-DB-B-1100 CASH / BANK");
                                $set("kredit", "NR-KR-C-1000 Hutang_Nominal");
                            } elseif ($state === "blj packaging") {
                                $set("debit", "LR-DB-F-2200 Blj Persediaan Packaging");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "blj utilitas produksi") {
                                $set("debit", "LR-DB-F-3200 Blj Utilitas Produksi");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya administrasi dan alat kantor") {
                                $set("debit", "LR-DB-F-4010 Biaya Administrasi dan Alat Kantor");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya tenaga kerja") {
                                $set("debit", "LR-DB-F-4020 Biaya Tenaga Kerja");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya tunjangan") {
                                $set("debit", "LR-DB-F-4030 Biaya Tunjangan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya transportasi") {
                                $set("debit", "LR-DB-F-4040 Biaya Transportasi");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya konsumsi") {
                                $set("debit", "LR-DB-F-4050 Biaya Konsumsi");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya advertising") {
                                $set("debit", "LR-DB-F-4060 Biaya Advertising");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya telepon dan pulsa") {
                                $set("debit", "LR-DB-F-4070 Biaya Telepon dan Pulsa");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya listrik dan air") {
                                $set("debit", "LR-DB-F-4080 Biaya Listrik dan Air");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya kebersihan dan keamanan") {
                                $set("debit", "LR-DB-F-4090 Biaya Kebersihan dan Keamanan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya sewa atau langganan") {
                                $set("debit", "LR-DB-F-4100 Biaya Sewa atau Langganan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "biaya instalasi perawatan dan perbaikan") {
                                $set("debit", "LR-DB-F-4110 Biaya Instalasi Perawatan dan Perbaikan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "beban overhead kantor") {
                                $set("debit", "LR-DB-G-1100 Beban Overhead Kantor");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "beban overhead lapangan") {
                                $set("debit", "LR-DB-G-1200 Beban Overhead Lapangan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "beban bencana") {
                                $set("debit", "LR-DB-G-3100 Beban Bencana");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "beban kesalahan kerja") {
                                $set("debit", "LR-DB-G-3200 Beban Kesalahan Kerja");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "pajak ppn") {
                                $set("debit", "LR-DB-G-9100 Pajak PPN");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "berikan piutang usaha ke mitra") {
                                $set("debit", "NR-DB-B-4000 Piutang Usaha");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "berikan piutang ke karyawan") {
                                $set("debit", "NR-DB-B-5000 Piutang Karyawan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "kembalikan modal") {
                                $set("debit", "NR-KR-D-1000 Modal");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "ajukan investasi") {
                                $set("debit", "NR-KR-D-2000 Investasi / Ekspansi Perusahaan");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "tarik prive") {
                                $set("debit", "NR-KR-D-3000 Prive");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bagikan dividen tunai") {
                                $set("debit", "NR-KR-D-9100 Sedekah / Donasi dari Dividen");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "sedekahkan") {
                                $set("debit", "NR-KR-D-9200 Sedekah / Donasi umum");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bayar hutang nominal") {
                                $set("debit", "NR-KR-C-1000 Hutang_Nominal");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bayar hutang usaha") {
                                $set("debit", "NR-KR-C-3000 Hutang_Usaha");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bayar hutang operasional") {
                                $set("debit", "NR-KR-C-4000 Hutang_Operasional");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bayar hutang aset tetap") {
                                $set("debit", "NR-KR-C-5000 Hutang_Aset_Tetap");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } elseif ($state === "bayar hutang pajak") {
                                $set("debit", "NR-KR-C-9000 Hutang_Pajak");
                                $set("kredit", "NR-DB-B-1100 CASH / BANK");
                            } else {
                                $set("debit", null);
                                $set("kredit", null);
                            }
                        })
                        ->live()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                    Select::make('debit')
                        ->label('Debit')
                        // ->default(null)
                        ->disabled(fn(Get $get) => $get('journal_type') === 'in' || $get('journal_type') === 'out' || $get('journal_type') === 'tf')
                        ->dehydrated()
                        ->required(fn(Get $get) => $get('mutation_type') !== null || $get('journal_type') === 'ps')
                        ->live()
                        ->options([
                            'NR-DB-A-1000 Tanah' => 'NR-DB-A-1000 Tanah',
                            'NR-DB-A-2000 Bangunan' => 'NR-DB-A-2000 Bangunan',
                            'NR-DB-A-3000 Peralatan' => 'NR-DB-A-3000 Peralatan',
                            'NR-DB-A-4000 Kendaraan' => 'NR-DB-A-4000 Kendaraan',
                            'NR-DB-A-1100 Akum. Tanah' => 'NR-DB-A-1100 Akum. Tanah',
                            'NR-DB-A-2100 Akum. Bangunan' => 'NR-DB-A-2100 Akum. Bangunan',
                            'NR-DB-A-3100 Akum. Peralatan' => 'NR-DB-A-3100 Akum. Peralatan',
                            'NR-DB-A-4100 Akum. Kendaraan' => 'NR-DB-A-4100 Akum. Kendaraan',

                            'NR-DB-B-1100 CASH / BANK' => 'NR-DB-B-1100 CASH / BANK',
                            'NR-DB-B-1900 DANA DISIMPAN' => 'NR-DB-B-1900 DANA DISIMPAN',
                            'NR-DB-B-2000 Persediaan Barang Dagang' => 'NR-DB-B-2000 Persediaan Barang Dagang',
                            'NR-DB-B-3000 Piutang Penjualan Barang' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                            'NR-DB-B-4000 Piutang Usaha' => 'NR-DB-B-4000 Piutang Usaha',
                            'NR-DB-B-5000 Piutang Karyawan' => 'NR-DB-B-5000 Piutang Karyawan',
                            'NR-DB-B-9000 Pajak Dibayar Dimuka' => 'NR-DB-B-9000 Pajak Dibayar Dimuka',

                            'NR-KR-C-1000 Hutang_Nominal' => 'NR-KR-C-1000 Hutang_Nominal',
                            'NR-KR-C-2000 Hutang_Pembelian_Barang' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'NR-KR-C-3000 Hutang_Usaha' => 'NR-KR-C-3000 Hutang_Usaha',
                            'NR-KR-C-4000 Hutang_Operasional' => 'NR-KR-C-4000 Hutang_Operasional',
                            'NR-KR-C-5000 Hutang_Aset_Tetap' => 'NR-KR-C-5000 Hutang_Aset_Tetap',
                            'NR-KR-C-9000 Hutang_Pajak' => 'NR-KR-C-9000 Hutang_Pajak',

                            'NR-KR-D-1000 Modal' => 'NR-KR-D-1000 Modal',
                            'NR-KR-D-2000 Investasi / Ekspansi Perusahaan' => 'NR-KR-D-2000 Investasi / Ekspansi Perusahaan',
                            'NR-KR-D-3000 Nominal Transfer' => 'NR-KR-D-3000 Nominal Transfer',
                            'NR-KR-D-3500 Barang Transfer' => 'NR-KR-D-3500 Barang Transfer',
                            'NR-KR-D-4000 Prive' => 'NR-KR-D-4000 Prive',
                            'NR-KR-D-5000 Laba Ditahan' => 'NR-KR-D-5000 Laba Ditahan',
                            'NR-KR-D-6000 Laba (Rugi) Berjalan' => 'NR-KR-D-6000 Laba (Rugi) Berjalan',
                            'NR-KR-D-7000 Dividen' => 'NR-KR-D-7000 Dividen',
                            'NR-KR-D-9100 Sedekah / Donasi dari Dividen' => 'NR-KR-D-9100 Sedekah / Donasi dari Dividen',
                            'NR-KR-D-9200 Sedekah / Donasi umum' => 'NR-KR-D-9200 Sedekah / Donasi umum',

                            'LR-KR-E-1000 Pendapatan Penjualan Kasir' => 'LR-KR-E-1000 Pendapatan Penjualan Kasir',
                            'LR-KR-E-2000 Pendapatan Penjualan di luar Kasir' => 'LR-KR-E-2000 Pendapatan Penjualan di luar Kasir',
                            'LR-KR-E-3000 Pendapatan Jasa' => 'LR-KR-E-3000 Pendapatan Jasa',
                            'LR-KR-E-4000 Pendapatan Sewa' => 'LR-KR-E-4000 Pendapatan Sewa',
                            'LR-KR-E-5000 Pendapatan Royalti' => 'LR-KR-E-5000 Pendapatan Royalti',
                            'LR-KR-E-8000 Pendapatan Bunga' => 'LR-KR-E-8000 Pendapatan Bunga',
                            'LR-KR-E-9100 Barang Terbeli' => 'LR-KR-E-9100 Barang Terbeli',
                            'LR-KR-E-9200 Barang Produksi Mengembang' => 'LR-KR-E-9200 Barang Produksi Mengembang',
                            'LR-KR-E-9300 Barang Stok Bertambah' => 'LR-KR-E-9300 Barang Stok Bertambah',

                            'LR-DB-F-1100 Barang Terjual' => 'LR-DB-F-1100 Barang Terjual',
                            'LR-DB-F-1200 Barang Produksi Menyusut' => 'LR-DB-F-1200 Barang Produksi Menyusut',
                            'LR-DB-F-1300 Barang Stok Berkurang' => 'LR-DB-F-1300 Barang Stok Berkurang',
                            'LR-DB-F-2100 Blj Persediaan Barang Dagang' => 'LR-DB-F-2100 Blj Persediaan Barang Dagang',
                            'LR-DB-F-2200 Blj Persediaan Packaging' => 'LR-DB-F-2200 Blj Persediaan Packaging',
                            'LR-DB-F-3100 Blj Bahan Baku Produksi' => 'LR-DB-F-3100 Blj Bahan Baku Produksi',
                            'LR-DB-F-3200 Blj Utilitas Produksi' => 'LR-DB-F-3100 Blj Utilitas Produksi',
                            'LR-DB-F-4010 Biaya Administrasi dan Alat Kantor' => 'LR-DB-F-4010 Biaya Administrasi dan Alat Kantor',
                            'LR-DB-F-4020 Biaya Tenaga Kerja' => 'LR-DB-F-4020 Biaya Tenaga Kerja',
                            'LR-DB-F-4030 Biaya Tunjangan' => 'LR-DB-F-4030 Biaya Tunjangan',
                            'LR-DB-F-4040 Biaya Transportasi' => 'LR-DB-F-4040 Biaya Transportasi',
                            'LR-DB-F-4050 Biaya Konsumsi' => 'LR-DB-F-4050 Biaya Konsumsi',
                            'LR-DB-F-4060 Biaya Advertising' => 'LR-DB-F-4060 Biaya Advertising',
                            'LR-DB-F-4070 Biaya Telepon dan Pulsa' => 'LR-DB-F-4070 Biaya Telepon dan Pulsa',
                            'LR-DB-F-4080 Biaya Listrik dan Air' => 'LR-DB-F-4080 Biaya Listrik dan Air',
                            'LR-DB-F-4090 Biaya Kebersihan dan Keamanan' => 'LR-DB-F-4090 Biaya Kebersihan dan Keamanan',
                            'LR-DB-F-4100 Biaya Sewa atau Langganan' => 'LR-DB-F-4100 Biaya Sewa atau Langganan',
                            'LR-DB-F-4110 Biaya Instalasi Perawatan dan Perbaikan' => 'LR-DB-F-4110 Biaya Instalasi Perawatan dan Perbaikan',

                            'LR-DB-G-1100 Beban Overhead Kantor' => 'LR-DB-G-1100 Beban Overhead Kantor',
                            'LR-DB-G-1200 Beban Overhead Lapangan' => 'LR-DB-G-1200 Beban Overhead Lapangan',
                            'LR-DB-G-2100 Beban Peny. Tanah' => 'LR-DB-G-2100 Beban Peny. Tanah',
                            'LR-DB-G-2200 Beban Peny. Bangunan' => 'LR-DB-G-2200 Beban Peny. Bangunan',
                            'LR-DB-G-2300 Beban Peny. Peralatan' => 'LR-DB-G-2300 Beban Peny. Peralatan',
                            'LR-DB-G-2400 Beban Peny. Kendaraan' => 'LR-DB-G-2400 Beban Peny. Kendaraan',
                            'LR-DB-G-3100 Beban Bencana' => 'LR-DB-G-3100 Beban Bencana',
                            'LR-DB-G-3200 Beban Kesalahan Kerja' => 'LR-DB-G-3200 Beban Kesalahan Kerja',
                            'LR-DB-G-9100 Pajak PPN' => 'LR-DB-G-9100 Pajak PPN',
                        ])
                        ->searchable()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                    Select::make('kredit')
                        ->label('Kredit')
                        // ->default(null)
                        ->disabled(fn(Get $get) => $get('journal_type') === 'in' || $get('journal_type') === 'out' || $get('journal_type') === 'tf')
                        ->dehydrated()
                        ->required(fn(Get $get) => $get('mutation_type') !== null || $get('journal_type') === 'ps')
                        ->live()
                        ->options([
                            'NR-DB-A-1000 Tanah' => 'NR-DB-A-1000 Tanah',
                            'NR-DB-A-2000 Bangunan' => 'NR-DB-A-2000 Bangunan',
                            'NR-DB-A-3000 Peralatan' => 'NR-DB-A-3000 Peralatan',
                            'NR-DB-A-4000 Kendaraan' => 'NR-DB-A-4000 Kendaraan',
                            'NR-DB-A-1100 Akum. Tanah' => 'NR-DB-A-1100 Akum. Tanah',
                            'NR-DB-A-2100 Akum. Bangunan' => 'NR-DB-A-2100 Akum. Bangunan',
                            'NR-DB-A-3100 Akum. Peralatan' => 'NR-DB-A-3100 Akum. Peralatan',
                            'NR-DB-A-4100 Akum. Kendaraan' => 'NR-DB-A-4100 Akum. Kendaraan',

                            'NR-DB-B-1100 CASH / BANK' => 'NR-DB-B-1100 CASH / BANK',
                            'NR-DB-B-1900 DANA DISIMPAN' => 'NR-DB-B-1900 DANA DISIMPAN',
                            'NR-DB-B-2000 Persediaan Barang Dagang' => 'NR-DB-B-2000 Persediaan Barang Dagang',
                            'NR-DB-B-3000 Piutang Penjualan Barang' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                            'NR-DB-B-4000 Piutang Usaha' => 'NR-DB-B-4000 Piutang Usaha',
                            'NR-DB-B-5000 Piutang Karyawan' => 'NR-DB-B-5000 Piutang Karyawan',
                            'NR-DB-B-9000 Pajak Dibayar Dimuka' => 'NR-DB-B-9000 Pajak Dibayar Dimuka',

                            'NR-KR-C-1000 Hutang_Nominal' => 'NR-KR-C-1000 Hutang_Nominal',
                            'NR-KR-C-2000 Hutang_Pembelian_Barang' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'NR-KR-C-3000 Hutang_Usaha' => 'NR-KR-C-3000 Hutang_Usaha',
                            'NR-KR-C-4000 Hutang_Operasional' => 'NR-KR-C-4000 Hutang_Operasional',
                            'NR-KR-C-5000 Hutang_Aset_Tetap' => 'NR-KR-C-5000 Hutang_Aset_Tetap',
                            'NR-KR-C-9000 Hutang_Pajak' => 'NR-KR-C-9000 Hutang_Pajak',

                            'NR-KR-D-1000 Modal' => 'NR-KR-D-1000 Modal',
                            'NR-KR-D-2000 Investasi / Ekspansi Perusahaan' => 'NR-KR-D-2000 Investasi / Ekspansi Perusahaan',
                            'NR-KR-D-3000 Nominal Transfer' => 'NR-KR-D-3000 Nominal Transfer',
                            'NR-KR-D-3500 Barang Transfer' => 'NR-KR-D-3500 Barang Transfer',
                            'NR-KR-D-4000 Prive' => 'NR-KR-D-4000 Prive',
                            'NR-KR-D-5000 Laba Ditahan' => 'NR-KR-D-5000 Laba Ditahan',
                            'NR-KR-D-6000 Laba (Rugi) Berjalan' => 'NR-KR-D-6000 Laba (Rugi) Berjalan',
                            'NR-KR-D-7000 Dividen' => 'NR-KR-D-7000 Dividen',
                            'NR-KR-D-9100 Sedekah / Donasi dari Dividen' => 'NR-KR-D-9100 Sedekah / Donasi dari Dividen',
                            'NR-KR-D-9200 Sedekah / Donasi umum' => 'NR-KR-D-9200 Sedekah / Donasi umum',

                            'LR-KR-E-1000 Pendapatan Penjualan Kasir' => 'LR-KR-E-1000 Pendapatan Penjualan Kasir',
                            'LR-KR-E-2000 Pendapatan Penjualan di luar Kasir' => 'LR-KR-E-2000 Pendapatan Penjualan di luar Kasir',
                            'LR-KR-E-3000 Pendapatan Jasa' => 'LR-KR-E-3000 Pendapatan Jasa',
                            'LR-KR-E-4000 Pendapatan Sewa' => 'LR-KR-E-4000 Pendapatan Sewa',
                            'LR-KR-E-5000 Pendapatan Royalti' => 'LR-KR-E-5000 Pendapatan Royalti',
                            'LR-KR-E-8000 Pendapatan Bunga' => 'LR-KR-E-8000 Pendapatan Bunga',
                            'LR-KR-E-9100 Barang Terbeli' => 'LR-KR-E-9100 Barang Terbeli',
                            'LR-KR-E-9200 Barang Produksi Mengembang' => 'LR-KR-E-9200 Barang Produksi Mengembang',
                            'LR-KR-E-9300 Barang Stok Bertambah' => 'LR-KR-E-9300 Barang Stok Bertambah',

                            'LR-DB-F-1100 Barang Terjual' => 'LR-DB-F-1100 Barang Terjual',
                            'LR-DB-F-1200 Barang Produksi Menyusut' => 'LR-DB-F-1200 Barang Produksi Menyusut',
                            'LR-DB-F-1300 Barang Stok Berkurang' => 'LR-DB-F-1300 Barang Stok Berkurang',
                            'LR-DB-F-2100 Blj Persediaan Barang Dagang' => 'LR-DB-F-2100 Blj Persediaan Barang Dagang',
                            'LR-DB-F-2200 Blj Persediaan Packaging' => 'LR-DB-F-2200 Blj Persediaan Packaging',
                            'LR-DB-F-3100 Blj Bahan Baku Produksi' => 'LR-DB-F-3100 Blj Bahan Baku Produksi',
                            'LR-DB-F-3200 Blj Utilitas Produksi' => 'LR-DB-F-3100 Blj Utilitas Produksi',
                            'LR-DB-F-4010 Biaya Administrasi dan Alat Kantor' => 'LR-DB-F-4010 Biaya Administrasi dan Alat Kantor',
                            'LR-DB-F-4020 Biaya Tenaga Kerja' => 'LR-DB-F-4020 Biaya Tenaga Kerja',
                            'LR-DB-F-4030 Biaya Tunjangan' => 'LR-DB-F-4030 Biaya Tunjangan',
                            'LR-DB-F-4040 Biaya Transportasi' => 'LR-DB-F-4040 Biaya Transportasi',
                            'LR-DB-F-4050 Biaya Konsumsi' => 'LR-DB-F-4050 Biaya Konsumsi',
                            'LR-DB-F-4060 Biaya Advertising' => 'LR-DB-F-4060 Biaya Advertising',
                            'LR-DB-F-4070 Biaya Telepon dan Pulsa' => 'LR-DB-F-4070 Biaya Telepon dan Pulsa',
                            'LR-DB-F-4080 Biaya Listrik dan Air' => 'LR-DB-F-4080 Biaya Listrik dan Air',
                            'LR-DB-F-4090 Biaya Kebersihan dan Keamanan' => 'LR-DB-F-4090 Biaya Kebersihan dan Keamanan',
                            'LR-DB-F-4100 Biaya Sewa atau Langganan' => 'LR-DB-F-4100 Biaya Sewa atau Langganan',
                            'LR-DB-F-4110 Biaya Instalasi Perawatan dan Perbaikan' => 'LR-DB-F-4110 Biaya Instalasi Perawatan dan Perbaikan',

                            'LR-DB-G-1100 Beban Overhead Kantor' => 'LR-DB-G-1100 Beban Overhead Kantor',
                            'LR-DB-G-1200 Beban Overhead Lapangan' => 'LR-DB-G-1200 Beban Overhead Lapangan',
                            'LR-DB-G-2100 Beban Peny. Tanah' => 'LR-DB-G-2100 Beban Peny. Tanah',
                            'LR-DB-G-2200 Beban Peny. Bangunan' => 'LR-DB-G-2200 Beban Peny. Bangunan',
                            'LR-DB-G-2300 Beban Peny. Peralatan' => 'LR-DB-G-2300 Beban Peny. Peralatan',
                            'LR-DB-G-2400 Beban Peny. Kendaraan' => 'LR-DB-G-2400 Beban Peny. Kendaraan',
                            'LR-DB-G-3100 Beban Bencana' => 'LR-DB-G-3100 Beban Bencana',
                            'LR-DB-G-3200 Beban Kesalahan Kerja' => 'LR-DB-G-3200 Beban Kesalahan Kerja',
                            'LR-DB-G-9100 Pajak PPN' => 'LR-DB-G-9100 Pajak PPN',
                        ])
                        ->searchable()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]),

                    ToggleButtons::make('payment_method')
                        ->options([
                            'cash' => 'Cash',
                            'transfer' => 'Transfer',
                        ])
                        ->default(null)
                        // ->required(fn(Get $get) => $get('debit') === 'NR-DB-B-1100 CASH / BANK' || $get('kredit') === 'NR-DB-B-1100 CASH / BANK')
                        ->required(function (Get $get) {
                            if ($get('journal_type') !== 'tf') {
                                if ($get('debit') === 'NR-DB-B-1100 CASH / BANK' || $get('kredit') === 'NR-DB-B-1100 CASH / BANK') {
                                    return true;
                                } else {
                                    return false;
                                }
                            } elseif ($get('journal_type') === 'tf') {
                                return false;
                            }
                        })
                        ->visible(function (Get $get) {
                            if ($get('journal_type') !== 'tf') {
                                if ($get('debit') === 'NR-DB-B-1100 CASH / BANK' || $get('kredit') === 'NR-DB-B-1100 CASH / BANK') {
                                    return true;
                                } else {
                                    return false;
                                }
                            } elseif ($get('journal_type') === 'tf') {
                                return false;
                            }
                        })
                        ->grouped()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('from_rekening', null);
                            $set('to_rekening', null);
                        })
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                    Select::make('from_rekening')
                        ->label('Rekening')
                        ->options(fn(Get $get): array => match ($get('payment_method')) {
                            'cash' => [
                                'KAS UTAMA' => 'KAS UTAMA',
                                'KAS KASIR' => 'KAS KASIR',
                                'KAS KECIL' => 'KAS KECIL',
                            ],
                            'transfer' => [
                                'BANK BCA' => 'BANK BCA',
                                'BANK BRI' => 'BANK BRI',
                            ],
                            default => [
                                'KAS UTAMA' => 'KAS UTAMA',
                                'KAS KASIR' => 'KAS KASIR',
                                'KAS KECIL' => 'KAS KECIL',
                                'BANK BCA' => 'BANK BCA',
                                'BANK BRI' => 'BANK BRI',
                            ],
                        })
                        ->searchable()
                        ->preload()
                        ->visible(fn(Get $get) => $get('debit') === 'NR-DB-B-1100 CASH / BANK' || $get('kredit') === 'NR-DB-B-1100 CASH / BANK')
                        ->required(fn(Get $get) => $get('debit') === 'NR-DB-B-1100 CASH / BANK' || $get('kredit') === 'NR-DB-B-1100 CASH / BANK')
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                    Select::make('to_branch_id')
                        ->label('ke Cabang')
                        ->relationship(
                            name: 'branch',
                            modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->where('partner_id', Auth::user()->partner_id)->where('is_active', 1),
                        )
                        ->options(Branch::query()->orderBy('name')->where('partner_id', Auth::user()->partner_id)->where('is_active', 1)->pluck('name', 'id'))
                        ->default(Auth::user()->branch_id)
                        ->searchable()
                        // ->getSearchResultsUsing(fn(string $search): array => Branch::query()
                        //     ->select([
                        //         DB::raw("CONCAT(name, ' ', phone) as nameNphone"),
                        //         'id',
                        //     ])
                        //     ->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->pluck('nameNphone', 'id')->toArray())
                        ->getOptionLabelUsing(fn($value): ?string => Branch::find($value)?->name)
                        ->preload()
                        ->hidden(fn(Get $get) => $get('journal_type') !== 'tf')
                        ->required()
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                    Select::make('to_rekening')
                        ->label('ke Rekening')
                        ->options(fn(Get $get): array => match ($get('payment_method')) {
                            'cash' => [
                                'KAS UTAMA' => 'KAS UTAMA',
                                'KAS KASIR' => 'KAS KASIR',
                                'KAS KECIL' => 'KAS KECIL',
                            ],
                            'transfer' => [
                                'BANK BCA' => 'BANK BCA',
                                'BANK BRI' => 'BANK BRI',
                            ],
                            default => [
                                'KAS UTAMA' => 'KAS UTAMA',
                                'KAS KASIR' => 'KAS KASIR',
                                'KAS KECIL' => 'KAS KECIL',
                                'BANK BCA' => 'BANK BCA',
                                'BANK BRI' => 'BANK BRI',
                            ],
                        })
                        // ->options([
                        //     'KAS UTAMA' => 'KAS UTAMA',
                        //     'KAS KASIR A' => 'KAS KASIR A',
                        //     'KAS KASIR B' => 'KAS KASIR B',
                        //     'KAS KASIR C' => 'KAS KASIR C',
                        //     'KAS KECIL A' => 'KAS KECIL A',
                        //     'KAS KECIL B' => 'KAS KECIL B',
                        //     'KAS KECIL C' => 'KAS KECIL C',
                        //     'BANK BSI' => 'BANK BSI',
                        //     'BANK BCA' => 'BANK BCA',
                        //     'BANK BRI' => 'BANK BRI',
                        //     'BANK BNI' => 'BANK BNI',
                        //     'BANK BTN' => 'BANK BTN',
                        //     'BANK MANDIRI' => 'BANK MANDIRI',
                        //     'BANK JATENG' => 'BANK JATENG',
                        // ])
                        ->searchable()
                        ->preload()
                        ->hidden(fn(Get $get) => $get('journal_type') !== 'tf')
                        ->required(fn(Get $get) => $get('journal_type') === 'tf')
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),


                    TextInput::make('nominal')
                        ->afterStateHydrated(function ($record) {
                            if (!is_null($record)) {
                                if (Auth::user()->branch_id != $record->branch_id) {
                                    return redirect('/admin/journals');
                                }
                            }
                        })
                        ->label('Nominal')
                        ->default(0)
                        ->required()
                        ->numeric()
                        ->live(debounce: 250) ## ini untuk delay 1000 milidetik lalu ada perubahan
                        ->columnSpan(['default' => 12, 'sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                ])->columns(['default' => 12, 'sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),
                //

                // Section::make('Ayat Jurnal')->schema([
                //     Repeater::make('payments')
                //         ->hiddenLabel()
                //         ->addable(false)
                //         ->relationship(
                //             name: 'payments',
                //             modifyQueryUsing: fn(Builder $query) => $query,
                //         )
                //         // ->reorderable()
                //         ->deleteAction(
                //             fn(Action $action) => $action->hidden(fn() => Auth::user()->level !== "backofficer"),
                //         )
                //         ->schema([

                //             Hidden::make('created_by')
                //                 ->default(fn() => Auth::user()->id)
                //                 ->required(),

                //             Hidden::make('updated_by')
                //                 ->default(null),

                //             Select::make('mutation_type')
                //                 ->searchable()
                //                 ->options(fn(Get $get): array => match ($get('../../journal_type')) {
                //                     'in' => [
                //                         'tambah modal' => 'tambah modal',
                //                         'tarik investasi' => 'tarik investasi',
                //                     ],
                //                     'out' => [
                //                         'kembalikan modal' => 'kembalikan modal',
                //                         'tambah investasi' => 'tambah investasi',
                //                     ],
                //                     'transfer' => [
                //                         'transfer dalam cabang' => 'transfer dalam cabang',
                //                         'transfer antar cabang' => 'transfer antar cabang',
                //                     ],
                //                     default => [],
                //                 })
                //                 // ->default(null)
                //                 ->columnSpan(['sm' => 8, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                //             Hidden::make('date_payment')->default(now()),

                //             // DateTimePicker::make('date_payment')
                //             //     ->disabled(function (Get $get) {
                //             //         if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                //             //             return true;
                //             //         }
                //             //     })
                //             //     ->default(now())
                //             //     ->required()
                //             //     ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                //             //     ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                //             //     ->columnSpan(['sm' => 8, 'md' => 8, 'lg' => 8, 'xl' => 8]),

                //             Select::make('currency')
                //                 ->disabled(function (Get $get) {
                //                     if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                //                         return true;
                //                     }
                //                 })
                //                 ->default('idr')
                //                 ->required()
                //                 ->options([
                //                     'idr' => 'IDR',
                //                     'usd' => 'USD',
                //                     'eur' => 'EUR'
                //                 ])
                //                 ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                //                 ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                //                 ->columnSpan(['sm' => 4, 'md' => 4, 'lg' => 4, 'xl' => 4]),

                //             ToggleButtons::make('payment_method')
                //                 ->disabled(function (Get $get) {
                //                     if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                //                         return true;
                //                     }
                //                 })
                //                 ->options([
                //                     'cash' => 'Cash',
                //                     'transfer' => 'Transfer',
                //                 ])
                //                 ->required()
                //                 ->grouped()
                //                 ->live()
                //                 ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                //                 ->columnSpan(['sm' => 5, 'md' => 5, 'lg' => 5, 'xl' => 5]),

                //             TextInput::make('nominal')
                //                 ->disabled(function (Get $get) {
                //                     if (Auth::user()->level !== "backofficer" && $get('id') != null) {
                //                         return true;
                //                     }
                //                 })
                //                 ->label('Nominal')
                //                 ->default(0)
                //                 ->required()
                //                 ->numeric()
                //                 ->live(debounce: 1000) ## ini untuk delay 1000 milidetik lalu ada perubahan
                //                 ->afterStateUpdated(fn(Set $set) => $set('updated_by', Auth::user()->id))
                //                 ->columnSpan(['sm' => 7, 'md' => 7, 'lg' => 7, 'xl' => 7]),

                //             Hidden::make('branch_id')
                //                 ->default(fn() => Auth::user()->branch_id)
                //                 // ->relationship(
                //                 //     name: 'branch',
                //                 //     modifyQueryUsing: fn(Builder $query) => $query->orderBy('name')->where('is_active', 1),
                //                 // )
                //                 // ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name}")
                //                 // ->disabled()
                //                 // ->dehydrated()
                //                 ->columnSpan(['sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2]),

                //         ])
                //         ->minItems(1)
                //         ->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]),
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->poll('10s')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('date_journal')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('notes')
                    ->label('Keterangan')
                    ->searchable(),
                TextColumn::make('journal_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'in' => 'IN',
                        'out' => 'OUT',
                        'tf' => 'TF',
                        'ps' => 'PS',
                        'sa' => 'SA',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'warning',
                        'tf' => 'info',
                        'ps' => 'danger',
                        'sa' => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'in' => 'heroicon-m-arrow-down',
                        'out' => 'heroicon-m-arrow-up',
                        'tf' => 'heroicon-m-arrows-right-left',
                        'ps' => 'heroicon-m-pause',
                        'sa' => 'heroicon-m-power',
                    })
                    ->sortable()
                    // ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('nominal')
                    ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800'])
                    ->numeric(locale: 'id')->prefix('Rp ')
                    ->alignRight()
                    ->sortable()
                    ->searchable()
                    ->summarize(Sum::make()->numeric(locale: 'id')->prefix('Rp ')->label('Nominal')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('userCre.name')
                    ->label('Created by')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('userUpd.name')
                    ->label('Updated by')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('date_journal', 'desc')
            // ->persistSortInSession()
            // ->persistFiltersInSession()
            // ->persistSearchInSession()
            // ->deselectAllRecordsWhenFiltered(false)
            ->defaultPaginationPageOption(25)
            ->paginated([
                10,
                25,
                50,
                100,
                500,
                // 1000,
                // 'all'
            ])

            ->filters([
                SelectFilter::make('journal_type')
                    ->multiple()
                    ->options([
                        'in' => 'IN',
                        'out' => 'OUT',
                        'tf' => 'TF',
                        'ps' => 'PS',
                        'sa' => 'SA',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->query(function (Journal $query) {
                return $query->where('branch_id', Auth::user()->branch_id);
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
            'view' => Pages\ViewJournal::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
