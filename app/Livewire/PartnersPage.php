<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

// #[Title('Mitra - TegarJaya')]

class PartnersPage extends Component
{
    #[Url()]
    public $cariBranch = '';

    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    // change Branch in User
    public function changeBranch($branch_id)
    {

        if (Auth::check()) {
            $data = User::where('id', auth()->user()->id);

            if (auth()->user()->is_admin == 1) {
                if (auth()->user()->level === 'backofficer' || auth()->user()->roles[0]->name === 'Seller') {
                    $update = [
                        'branch_id' => $branch_id,
                    ];
                } else {
                    $update = [
                        'branch_id' => auth()->user()->branch_id,
                    ];
                }
            } else {
                $update = [
                    'branch_id' => $branch_id,
                    'partner_id' => Branch::find($branch_id)->partner_id,
                ];
            }
            $data->update($update);
            return $this->redirect('/', navigate: true);
        } else {
            return $this->redirect('/products?branch=' . $branch_id, navigate: true);
        }
    }

    public function render()
    {
        $dynamicTitle = "Mitra - " . Partner::where('slug', $this->slug)->value('name');

        $mitra = Partner::where('slug', $this->slug)->get()->first();
        $par_id = Partner::where('slug', $this->slug)->value('id');
        $branchQuery = Branch::query()->where('is_active', 1)->where('partner_id', $par_id);

        return view('livewire.partners-page', [
            'mitra' => $mitra,
            'branches' => $branchQuery,
            'cariBranch' => $this->cariBranch
        ])->title($dynamicTitle);
    }
}
