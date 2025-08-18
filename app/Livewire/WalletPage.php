<?php

namespace App\Livewire;

use App\Models\Journal;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

// use Livewire\WithPagination;

#[Title('Dompet - TegarJaya')]

class WalletPage extends Component
{
    use WithPagination;

    #[Url()]
    public $rek;

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
    }
    public function render()
    {
        $paymentByDate = Payment::where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')
            ->selectRaw("COUNT(*) date_payment, DATE_FORMAT(date_payment, '%Y-%m-%d') date")
            ->orderByDesc('date')
            ->groupBy('date');

        // dd($paymentByDate);

        $journal = Journal::all();
        $paymentAll = Payment::where('branch_id', Auth::user()->branch_id);
        $cashBankDb = Payment::where('branch_id', Auth::user()->branch_id)->where('debit', 'NR-DB-B-1100 CASH / BANK')->where('rekening', $this->rek)->sum('nominal');
        $cashBankKr = Payment::where('branch_id', Auth::user()->branch_id)->where('kredit', 'NR-DB-B-1100 CASH / BANK')->where('rekening', $this->rek)->sum('nominal');
        $cashBankTotal = $cashBankDb - $cashBankKr;

        $cashBankHistories = Payment::query()->where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('date_payment', 'desc');
        $cashBankHistoriesLast = Payment::query()->where('branch_id', Auth::user()->branch_id)->whereNotNull('rekening')->where('rekening', 'LIKE', '%' . $this->rek . '%')->orderBy('date_payment', 'desc')->value('date_payment');

        return view('livewire.wallet-page', [
            'paymentByDate' => $paymentByDate->paginate(5),
            'journal' => $journal,
            'paymentAll' => $paymentAll,
            'cashBankTotal' => $cashBankTotal,
            'cashBankHistories' => $cashBankHistories->get(),
            'cashBankHistoriesLast' => $cashBankHistoriesLast,
        ]);
    }
}
