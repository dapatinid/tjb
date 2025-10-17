<?php

namespace App\Livewire;

use App\Models\Journal;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

// use Livewire\WithPagination;

#[Title('Dompet - TegarJaya')]

class WalletPage extends Component
{
    use WithPagination;

    #[Url()]
    public $rek;
    public $date_option;

    public $laporan_dompet_by_date;

    public function mount()
    {
        if (Auth::check()) {
            if (Auth::user()->level == null) {
                return $this->redirect('/', navigate: true);
            }
        } else {
            return $this->redirect('/', navigate: true);
        }

        if ($this->rek == null || $this->rek === '') {
            $this->rek = 'KAS KASIR';
        }

        // $this->laporan_dompet_by_date = '02/09/2025';
    }

    public function exportDompetbyDateTransaksi()
    {
        if ($this->laporan_dompet_by_date == null || $this->laporan_dompet_by_date == '') {
            LivewireAlert::title('Tanggal Kosong')
                ->show();
        } else {
            return redirect()->route('exportdompetbydatetransaksi', http_build_query(array(
                'bydate' => $this->laporan_dompet_by_date,
                'rek' => $this->rek
            )));
        }
    }
    public function exportDompetbyDateDibuat()
    {
        if ($this->laporan_dompet_by_date == null || $this->laporan_dompet_by_date == '') {
            LivewireAlert::title('Tanggal Kosong')
                ->show();
        } else {
            return redirect()->route('exportdompetbydatedibuat', http_build_query(array(
                'bydate' => $this->laporan_dompet_by_date,
                'rek' => $this->rek
            )));
        }
    }
    public function exportDompetbyDateDiedit()
    {
        if ($this->laporan_dompet_by_date == null || $this->laporan_dompet_by_date == '') {
            LivewireAlert::title('Tanggal Kosong')
                ->show();
        } else {
            return redirect()->route('exportdompetbydatediedit', http_build_query(array(
                'bydate' => $this->laporan_dompet_by_date,
                'rek' => $this->rek
            )));
        }
    }

    public function render()
    {

        $journal = Journal::all();
        $cashBankDb = Payment::where('branch_id', Auth::user()->branch_id)->where('debit', 'NR-DB-B-1100 CASH / BANK')->where('rekening', $this->rek)->sum('nominal');
        $cashBankKr = Payment::where('branch_id', Auth::user()->branch_id)->where('kredit', 'NR-DB-B-1100 CASH / BANK')->where('rekening', $this->rek)->sum('nominal');
        $cashBankTotal = $cashBankDb - $cashBankKr;

        if ($this->date_option === 'created_at') {
        $cashBankHistories = Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('created_at', 'desc');
        $cashBankHistoriesLast = Payment::query()->where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('created_at', 'desc')->value('created_at');
        } elseif ($this->date_option === 'updated_at') {
        $cashBankHistories = Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('updated_at', 'desc');
        $cashBankHistoriesLast = Payment::query()->where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('updated_at', 'desc')->value('updated_at');
        } else {
        $cashBankHistories = Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('date_payment', 'desc');
        $cashBankHistoriesLast = Payment::query()->where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('date_payment', 'desc')->value('date_payment');
        }

        return view('livewire.wallet-page', [
            'journal' => $journal,
            'cashBankTotal' => $cashBankTotal,
            'cashBankHistories' => $cashBankHistories->paginate(20),
            'cashBankHistoriesLast' => $cashBankHistoriesLast,
        ]);
    }
}
