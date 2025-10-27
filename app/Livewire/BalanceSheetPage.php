<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

use function PHPUnit\Framework\isNull;

#[Title('Neraca - TegarJaya')]

class BalanceSheetPage extends Component
{
    use WithPagination;

    #[Url()]
    public $date_awal = '';
    #[Url()]
    public $date_akhir = '';

    public function mount()
    {
        if (Auth::check()) {

            if (Auth::user()->is_admin == 1) {
                if (Auth::user()->level === 'frontliner' ||  Auth::user()->roles[0]->name === "Kasir") {
                    return $this->redirect('/dompet', navigate: true);
                }
            } else {
                return $this->redirect('/dompet', navigate: true);
            }
        } else {
            return $this->redirect('/dompet', navigate: true);
        }

        if ($this->date_awal == '' || $this->date_akhir == '') {
            // $date_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $date_awal = '2000-01-01';
            $date_akhir = Carbon::now()->lastOfMonth()->format('Y-m-d');
            $this->date_awal = $date_awal;
            $this->date_akhir = $date_akhir;
        }
    }

    // change Branch in User
    public function changeBranch($branch_id)
    {

        $data = User::where('id', Auth::user()->id);

        if (Auth::user()->is_admin == 1) {
            if (Auth::user()->level === 'backofficer') {
                $update = [
                    'branch_id' => $branch_id,
                ];
            } else {
                $update = [
                    'branch_id' => Auth::user()->branch_id,
                ];
            }
        }
        $data->update($update);

        return $this->redirect('/neraca', navigate: true);
    }

    public function render()
    {
        $balance = Payment::all()->where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59']);

        //////// Aset Tetap

        $nr_AktivaAtDebit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%NR-DB-A%')
            ->get()->pluck('debit');
        $nr_AktivaAtKredit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%NR-DB-A%')
            ->get()->pluck('kredit');

        $nr_AktivaAsetTetap = $nr_AktivaAtDebit->merge($nr_AktivaAtKredit);

        //////// Aset Lancar

        $nr_AktivaAlDebit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%NR-DB-B%')
            ->get()->pluck('debit');
        $nr_AktivaAlKredit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%NR-DB-B%')
            ->get()->pluck('kredit');

        $nr_AktivaAsetLancar = $nr_AktivaAlDebit->merge($nr_AktivaAlKredit);

        //////// Kewajiban

        $nr_PasivaKwDebit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%NR-KR-C%')
            ->get()->pluck('debit');
        $nr_PasivaKwKredit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%NR-KR-C%')
            ->get()->pluck('kredit');

        $nr_PasivaKewajiban = $nr_PasivaKwDebit->merge($nr_PasivaKwKredit);

        //////// Ekuitas

        $nr_PasivaEkDebit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%NR-KR-D%')
            ->get()->pluck('debit');
        $nr_PasivaEkKredit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%NR-KR-D%')
            ->get()->pluck('kredit');

        $nr_PasivaEkuitas = $nr_PasivaEkDebit->merge($nr_PasivaEkKredit);

        //////// LABA RUGI /////////

        $profitLoss = Payment::all()->where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59']);

        // $pl_kredit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%LR-KR-%')
        //     ->groupBy('kredit')
        //     ->selectRaw('count(*) as nominal, kredit')
        //     ->orderBy('kredit', 'asc')->get();
        // $pl_debit = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%LR-DB-%')
        //     ->groupBy('debit')
        //     ->selectRaw('count(*) as nominal, debit')
        //     ->orderBy('debit', 'asc')->get();

        $pl_kredit_total = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%LR-KR-%')->sum('nominal') - Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%LR-KR-%')->sum('nominal');
        $pl_debit_total = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%LR-DB-%')->sum('nominal') - Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%LR-DB-%')->sum('nominal');

        $branchQuery = Branch::query()->where('is_active', 1);
        $mitra = Partner::all();

        return view('livewire.balance-sheet-page', [
            'balance' => $balance,

            'nr_AktivaAsetTetap' => $nr_AktivaAsetTetap->unique(),
            'nr_AktivaAsetLancar' => $nr_AktivaAsetLancar->unique(),
            'nr_PasivaKewajiban' => $nr_PasivaKewajiban->unique(),
            'nr_PasivaEkuitas' => $nr_PasivaEkuitas->unique(),

            'profitLoss' => $profitLoss,
            // 'pl_kredit' => $pl_kredit,
            // 'pl_debit' => $pl_debit,
            'pl_kredit_total' => $pl_kredit_total,
            'pl_debit_total' => $pl_debit_total,

            'mitra' => $mitra,
            'branches' => $branchQuery,
        ]);
    }
}
