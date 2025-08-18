<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Toko - TegarJaya')]
class BranchesPage extends Component
{
    #[Url()]
    public $cariBranch = '';

    // change Branch in User
    public function changeBranch($branch_id)
    {

        if (Auth::check()) {
            $data = User::where('id', Auth::user()->id);

            if (Auth::user()->is_admin == 1) {
                if (auth()->user()->level === 'backofficer' || auth()->user()->roles[0]->name === 'Seller') {
                    $update = [
                        'branch_id' => $branch_id,
                    ];
                } else {
                    $update = [
                        'branch_id' => Auth::user()->branch_id,
                    ];
                }
            } else {
                $update = [
                    'branch_id' => $branch_id,
                    'partner_id' => Branch::find($branch_id)->partner_id,
                ];
            }
            $data->update($update);

            return $this->redirect('/products?branch=' . $branch_id, navigate: true);
        } else {
            return $this->redirect('/products?branch=' . $branch_id, navigate: true);
        }
    }

    public function render()
    {
        $branchQuery = Branch::query()->where('is_active', 1);
        $mitra = Partner::all();

        return view('livewire.branches-page', [
            'mitra' => $mitra,
            'branches' => $branchQuery,
            'cariBranch' => $this->cariBranch
        ]);
    }
}
