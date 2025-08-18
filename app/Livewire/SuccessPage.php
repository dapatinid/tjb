<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;

#[Title('Success - TegarJaya')]
class SuccessPage extends Component
{
    public function render()
    {
        $isadmin = auth()->user()->is_admin;
        if ($isadmin == 1) {
            $latest_order = Order::with('address')->where('created_by', auth()->user()->id)->latest()->first();
        }
        if ($isadmin == 0) {
            $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();
        }
        $states = Province::all();
        $cities = City::all();
        $districts = District::all();
        $villages = Village::all();
        $user = User::all();
        return view('livewire.success-page', [
            'order' => $latest_order,
            'states' => $states,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'user' => $user,
        ]);
    }
}
