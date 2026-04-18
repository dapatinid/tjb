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

#[Title('Laba Rugi - TegarJaya')]
class ProfitLossAllPage extends Component
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

        // ✅ Default ke hari ini jika kosong
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

        return $this->redirect('/laba-rugi', navigate: true);
    }

    public function render()
    {
        $user      = Auth::user();
        $branchId  = $user->branch_id;
        $dateStart = $this->date_awal  . ' 00:00:00';
        $dateEnd   = $this->date_akhir . ' 23:59:59';

        // ✅ Base query — reusable, tidak load ke memori
        $base = Payment::whereBetween('date_payment', [$dateStart, $dateEnd]);

        // ✅ profitLoss: query ke DB langsung, bukan ::all()
        $profitLoss = (clone $base)->get();

        // Kredit & Debit grouped
        $pl_kredit = (clone $base)
            ->where('kredit', 'like', '%LR-KR-%')
            ->groupBy('kredit')
            ->selectRaw('kredit, SUM(nominal) as nominal, COUNT(*) as jumlah_transaksi')
            ->orderBy('kredit')
            ->get();

        $pl_debit = (clone $base)
            ->where('debit', 'like', '%LR-DB-%')
            ->groupBy('debit')
            ->selectRaw('debit, SUM(nominal) as nominal, COUNT(*) as jumlah_transaksi')
            ->orderBy('debit')
            ->get();

        // ✅ Total: hitung di DB, bukan di collection
        $pl_kredit_total = (clone $base)->where('kredit', 'like', '%LR-KR-%')->sum('nominal')
                         - (clone $base)->where('debit',  'like', '%LR-KR-%')->sum('nominal');

        $pl_debit_total  = (clone $base)->where('debit',  'like', '%LR-DB-%')->sum('nominal')
                         - (clone $base)->where('kredit', 'like', '%LR-DB-%')->sum('nominal');

        $branches = Branch::where('is_active', 1);

        return view('livewire.profit-loss-all-page', [
            'profitLoss'      => $profitLoss,
            'pl_kredit'       => $pl_kredit,
            'pl_debit'        => $pl_debit,
            'pl_kredit_total' => $pl_kredit_total,
            'pl_debit_total'  => $pl_debit_total,
            'branches'        => $branches,
        ]);
    }
}