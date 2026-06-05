<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Neraca - TegarJaya')]
class BalanceSheetAllPage extends Component
{
    #[Url()]
    public $date_awal = '';

    #[Url()]
    public $date_akhir = '';

    public function mount()
    {
        if (!Auth::check()) {
            return $this->redirect('/dompet', navigate: true);
        }

        $user = Auth::user();

        if ($user->is_admin != 1) {
            return $this->redirect('/dompet', navigate: true);
        }

        if ($user->level === 'frontliner' || ($user->roles[0]->name ?? '') === 'Kasir') {
            return $this->redirect('/dompet', navigate: true);
        }

        if ($this->date_awal === '' || $this->date_akhir === '') {
            $this->date_awal  = Carbon::now()->format('Y-m-d');
            $this->date_akhir = Carbon::now()->format('Y-m-d');
        }
    }

    public function changeBranch($branch_id)
    {
        $user = Auth::user();
        if ($user->is_admin == 1 && $user->level === 'backofficer') {
            User::where('id', $user->id)->update(['branch_id' => $branch_id]);
        }
        return $this->redirect('/neraca', navigate: true);
    }

    // ✅ Helper: hitung net per akun dari hasil query DB (bukan collection besar)
    private function buildAccountTotals(string $pattern, string $side): array
    {
        $branchId  = Auth::user()->branch_id;
        $start     = $this->date_awal  . ' 00:00:00';
        $end       = $this->date_akhir . ' 23:59:59';

        // Ambil semua kode akun unik (dari debit & kredit)
        $debitCodes  = Payment::whereBetween('date_payment', [$start, $end])
            ->where('debit', 'like', "%{$pattern}%")
            ->distinct()->pluck('debit');

        $kreditCodes = Payment::whereBetween('date_payment', [$start, $end])
            ->where('kredit', 'like', "%{$pattern}%")
            ->distinct()->pluck('kredit');

        $allCodes = $debitCodes->merge($kreditCodes)->unique()->values();

        // Untuk setiap kode, hitung sum debit & kredit sekaligus di DB
        $result = [];
        foreach ($allCodes as $code) {
            $sumDebit  = Payment::whereBetween('date_payment', [$start, $end])
                ->where('debit', $code)->sum('nominal');

            $sumKredit = Payment::whereBetween('date_payment', [$start, $end])
                ->where('kredit', $code)->sum('nominal');

            $net = $side === 'debit'
                ? ($sumDebit - $sumKredit)
                : ($sumKredit - $sumDebit);

            $result[$code] = $net;
        }

        return $result;
    }

    public function render()
    {
        $branchId  = Auth::user()->branch_id;
        $dateStart = $this->date_awal  . ' 00:00:00';
        $dateEnd   = $this->date_akhir . ' 23:59:59';

        // ✅ Base query builder — tidak load ke memori
        $base = Payment::whereBetween('date_payment', [$dateStart, $dateEnd]);

        // ✅ Semua sum langsung di DB
        $pl_kredit_total = (clone $base)->where('kredit', 'like', '%LR-KR-%')->sum('nominal')
                         - (clone $base)->where('debit',  'like', '%LR-KR-%')->sum('nominal');

        $pl_debit_total  = (clone $base)->where('debit',  'like', '%LR-DB-%')->sum('nominal')
                         - (clone $base)->where('kredit', 'like', '%LR-DB-%')->sum('nominal');

        // ✅ Akun per kategori: [kode => net_nominal]
        $nr_AktivaAsetTetap  = $this->buildAccountTotals('NR-DB-A', 'debit');
        $nr_AktivaAsetLancar = $this->buildAccountTotals('NR-DB-B', 'debit');
        $nr_PasivaKewajiban  = $this->buildAccountTotals('NR-KR-C', 'kredit');
        $nr_PasivaEkuitas    = $this->buildAccountTotals('NR-KR-D', 'kredit');

        $branches = Branch::where('is_active', 1);

        return view('livewire.balance-sheet-all-page', [
            'nr_AktivaAsetTetap'  => $nr_AktivaAsetTetap,
            'nr_AktivaAsetLancar' => $nr_AktivaAsetLancar,
            'nr_PasivaKewajiban'  => $nr_PasivaKewajiban,
            'nr_PasivaEkuitas'    => $nr_PasivaEkuitas,
            'pl_kredit_total'     => $pl_kredit_total,
            'pl_debit_total'      => $pl_debit_total,
            'branches'            => $branches,
        ]);
    }
}