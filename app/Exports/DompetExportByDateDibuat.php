<?php

namespace App\Exports;

use App\Models\Journal;
use Illuminate\Support\Str;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DompetExportByDateDibuat implements FromCollection, WithMapping, WithHeadings
{
    protected $bydate;
    protected $rek;

    function __construct($byfilter)
    {
        $byfilter = Str::of($byfilter)->explode('&');
        $bydate = Str::after($byfilter[0], '=');
        $rek = Str::after($byfilter[1], '=');
        $rek = Str::replace('+', ' ', $rek);
        $this->bydate = $bydate;
        $this->rek = $rek;
        // dd($this->rek);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Yang Bersangkutan',
            'Waktu Transaksi',
            'Keterangan',
            'Nominal',
            'Pembuat',
            'Pengedit',
            'Pertama Dibuat',
            'Terakhir Diedit',
        ];
    }

    public function map($dompet): array
    {
        $journal = Journal::all();
        $keterangn = $dompet->paymentable_type === 'App\Models\Journal' ? $journal->where('id', $dompet->paymentable_id)->value('notes')  :  $dompet->mutation_type;

        if ($dompet->debit === "NR-DB-B-1100 CASH / BANK") {
            $nominal = $dompet->nominal;
        } else {
            $nominal = $dompet->nominal * -1;
        }


        return [
            $dompet->id,
            $dompet->user->name,
            $dompet->date_payment,
            $keterangn,
            $nominal,
            $dompet->userCre->name,
            $dompet->userUpd->name,
            $dompet->created_at,
            $dompet->updated_at,

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->bydate != null) {
            return Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('created_at', 'desc')->whereBetween('created_at', [$this->bydate . ' 00:00:00', $this->bydate . ' 23:59:59'])->get();
        } else {
            return Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('created_at', 'desc')->get();
        }
    }
}
