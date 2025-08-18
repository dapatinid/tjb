<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;

class MyAccountEditPage extends Component
{
    use WithFileUploads;

    #[Validate('image|max:1024|mimes:png,jpg,jpeg')] // 1MB Max
    public $photo;

    #[Url()]
    public $name;
    #[Url()]
    public $phone;
    #[Url()]
    public $email;
    public $password = '';

    public $state;
    public $city;
    public $district;
    public $village;
    public $street_address;
    public $zip_code;

    public function mount()
    {

        $this->name = User::where('id', auth()->user()->id)->value('name');
        $this->phone = User::where('id', auth()->user()->id)->value('phone');
        $this->email = User::where('id', auth()->user()->id)->value('email');

        $this->street_address = User::where('id', auth()->user()->id)->value('street_address');
        $this->zip_code = User::where('id', auth()->user()->id)->value('zip_code');

        if (auth()->user()->village != '') {
            $this->village = auth()->user()->village;
        }
        if (auth()->user()->district != '') {
            $this->district = auth()->user()->district;
        }
        if (auth()->user()->city != '') {
            $this->city = auth()->user()->city;
        }
        if (auth()->user()->state != '') {
            $this->state = auth()->user()->state;
        }
    }

    // update user
    public function updateMyAccount()
    {
        if ($this->photo != null) {
            if (auth()->user()->image) {
                Storage::disk('public')->delete(auth()->user()->image);
            };
            $nameImg = md5($this->photo . microtime()) . '.' . $this->photo->extension();
            $this->photo->storeAs('users/' . auth()->user()->id, $nameImg);
        } else {
            $nameImg = auth()->user()->image;
        }


        $this->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'district' => 'required|max:255',
            'village' => 'required|max:255',
            'street_address' => 'required|max:255',
        ]);

        $data = User::where('id', auth()->user()->id);

        if ($this->photo != null) {
            if ($this->password != '') {
                $validate = [
                    'image' =>  'users/' . auth()->user()->id . '/' . $nameImg,
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'password' => Hash::make($this->password),
                    'state' => $this->state,
                    'city' => $this->city,
                    'district' => $this->district,
                    'village' => $this->village,
                    'street_address' => $this->street_address,
                    'zip_code' => $this->zip_code,
                ];
            } else {
                $validate = [
                    'image' =>  'users/' . auth()->user()->id . '/' . $nameImg,
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'state' => $this->state,
                    'city' => $this->city,
                    'district' => $this->district,
                    'village' => $this->village,
                    'street_address' => $this->street_address,
                    'zip_code' => $this->zip_code,
                ];
            }
        } else {
            if ($this->password != '') {
                $validate = [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'password' => Hash::make($this->password),
                    'state' => $this->state,
                    'city' => $this->city,
                    'district' => $this->district,
                    'village' => $this->village,
                    'street_address' => $this->street_address,
                    'zip_code' => $this->zip_code,
                ];
            } else {
                $validate = [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'state' => $this->state,
                    'city' => $this->city,
                    'district' => $this->district,
                    'village' => $this->village,
                    'street_address' => $this->street_address,
                    'zip_code' => $this->zip_code,
                ];
            }
        }


        $data->update($validate);

        LivewireAlert::title('Item Saved')
            ->text('Data has been successfully saved.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    public function changeState()
    {
        $this->city = "";
        $this->district = "";
        $this->village = "";
    }
    public function changeCity()
    {
        $this->district = "";
        $this->village = "";
    }
    public function changeDistrict()
    {
        $this->village = "";
    }

    public function render()
    {
        $states = Province::all();
        $cities = City::all()->where('province_code', $this->state)->sortByDesc('name');
        $districts = District::all()->where('city_code', $this->city)->sortBy('name');
        $villages = Village::all()->where('district_code', $this->district)->sortBy('name');
        $kab = City::all()->sortByDesc('name');
        $kec = District::all()->sortBy('name');
        $desa = Village::all()->sortBy('name');

        $users = User::all();

        return view('livewire.my-account-edit-page', [
            'states' => $states,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'users' => $users,
            'kab' => $kab,
            'kec' => $kec,
            'desa' => $desa,
        ]);
    }
}
