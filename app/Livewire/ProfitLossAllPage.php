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

#[Title('Laba Rugi - TegarJaya')]

class ProfitLossAllPage extends Component
{
    use WithPagination;

    public $date_awal = '';
    public $date_akhir = '';
    public $branchByPartner = [];

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
            $date_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
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

        return $this->redirect('/laba-rugi', navigate: true);
    }

    public function render()
    {
        $branchByPartner = Branch::where('partner_id', Auth::user()->partner_id)->get()->pluck('id');
        // dd($branchByPartner);

        $profitLoss = Payment::all()->whereIn('branch_id', $branchByPartner)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59']);
        $pl_kredit = Payment::whereIn('branch_id', $branchByPartner)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%LR-KR-%')
            ->groupBy('kredit')
            ->selectRaw('count(*) as nominal, kredit')
            ->orderBy('kredit', 'asc')->get();
        $pl_debit = Payment::whereIn('branch_id', $branchByPartner)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%LR-DB-%')
            ->groupBy('debit')
            ->selectRaw('count(*) as nominal, debit')
            ->orderBy('debit', 'asc')->get();
        $pl_kredit_total = Payment::whereIn('branch_id', $branchByPartner)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('kredit', 'like', '%LR-KR-%')->sum('nominal');
        $pl_debit_total = Payment::whereIn('branch_id', $branchByPartner)->whereBetween('date_payment', [$this->date_awal . ' 00:00:00', $this->date_akhir . ' 23:59:59'])->where('debit', 'like', '%LR-DB-%')->sum('nominal');

        $branchQuery = Branch::query()->where('is_active', 1);
        $mitra = Partner::all();

        return view('livewire.profit-loss-all-page', [
            'profitLoss' => $profitLoss,
            'pl_kredit' => $pl_kredit,
            'pl_debit' => $pl_debit,
            'pl_kredit_total' => $pl_kredit_total,
            'pl_debit_total' => $pl_debit_total,

            'mitra' => $mitra,
            'branches' => $branchQuery,
        ]);
    }
}
