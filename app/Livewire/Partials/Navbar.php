<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Partner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $total_count = 0;

    public function mount()
    {
        $this->total_count = count(CartManagement::getCartItemsFromCart());
    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count)
    {
        $this->total_count = $total_count;
    }

    public function render()
    {
        // cek Branch
        if (Auth::check()) {
            $thisBranch = Branch::where('partner_id', auth()->user()->partner_id)->where('is_active', 1)->get();
            $thisPartner = Partner::all();
        } else {
            $thisBranch = Branch::all()->where('partner_id', 1)->where('is_active', 1);
            $thisPartner = Partner::where('id', 1)->value('slug');
        }

        // Cek New Events
        $is_new_event = (Event::whereNotNull('date_from')
            ->whereNowOrFuture('date_from')
            ->orderByDesc('date_from')->get()->count() > 0
        ) ? Event::whereNotNull('date_from')
            ->whereNowOrFuture('date_from')
            ->orderByDesc('date_from')->get()->count() . ' next' : '';

        //////////// API JADWAL SHOLAT

        $today = Carbon::today()->translatedFormat('l, d - m - Y');

        // // https://api.myquran.com/v2/sholat/jadwal/1413/2025/8/7  # misal lokasi KENDAL tgl 2025 Mei 3
        // $api_url = 'https://api.myquran.com/v2/sholatt/jadwal/1413/' . date('Y') . '/' . date('m') . '/' . date('d') . '';

        // // membaca JSON dari url
        // $json_data = file_get_contents($api_url);

        // if ($json_data) {
        //     $response_data_php = array(
        //         "status" => true,
        //         "request" => ["path" => "/sholat/jadwal/1413/2025/08/13"],
        //         "data" =>
        //         [
        //             "id" => 1413,
        //             "lokasi" => "KAB. KENDAL",
        //             "daerah" => "JAWA TENGAH",
        //             "jadwal" =>
        //             [
        //                 "tanggal" => "Rabu, 13/08/2025",
        //                 "imsak" => "04:21",
        //                 "subuh" => "04:31",
        //                 "terbit" => "05:45",
        //                 "dhuha" => "06:13",
        //                 "dzuhur" => "11:48",
        //                 "ashar" => "15:08",
        //                 "maghrib" => "17:43",
        //                 "isya" => "18:54",
        //                 "date" => "2025-08-13"
        //             ]
        //         ]
        //     );
        //     $jadwal_shalat = $response_data_php['data'];
        //     $subuh = $jadwal_shalat['jadwal']['subuh'];
        //     $terbit = $jadwal_shalat['jadwal']['terbit'];
        //     $dhuha = $jadwal_shalat['jadwal']['dhuha'];
        //     $dzuhur = $jadwal_shalat['jadwal']['dzuhur'];
        //     $ashar = $jadwal_shalat['jadwal']['ashar'];
        //     $maghrib = $jadwal_shalat['jadwal']['maghrib'];
        //     $isya = $jadwal_shalat['jadwal']['isya'];
        // } else {

        //     // Decode data JSON data menjadi array PHP
        //     $response_data = json_decode($json_data);
        //     // Mengakses data yang ada dalam object 'data'
        //     $jadwal_shalat = $response_data->data;
        //     $subuh = $jadwal_shalat->jadwal->subuh;
        //     $terbit = $jadwal_shalat->jadwal->terbit;
        //     $dhuha = $jadwal_shalat->jadwal->dhuha;
        //     $dzuhur = $jadwal_shalat->jadwal->dzuhur;
        //     $ashar = $jadwal_shalat->jadwal->ashar;
        //     $maghrib = $jadwal_shalat->jadwal->maghrib;
        //     $isya = $jadwal_shalat->jadwal->isya;
        // }

        return view('livewire.partials.navbar', [
            'thisBranch' => $thisBranch,

            // 'subuh' => $subuh,
            // 'terbit' => $terbit,
            // 'dhuha' => $dhuha,
            // 'dzuhur' => $dzuhur,
            // 'ashar' => $ashar,
            // 'maghrib' => $maghrib,
            // 'isya' => $isya,

            'today' => $today,
            'is_new_event' => $is_new_event,
            'thisPartner' => $thisPartner,
        ]);
    }
}
