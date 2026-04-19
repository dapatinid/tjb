<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\TrStkOut;
use App\Models\Village;

class PrintController extends Controller
{
    // ✅ Helper: auth guard admin
    private function guardAdmin(string $redirectTo = '/my-orders'): void
    {
        if (Auth::check() && auth()->user()->is_admin == 0) {
            redirect($redirectTo)->send();
            exit;
        }
    }

    // ✅ Helper: ambil data geografis hanya kode yang relevan
private function geoData(?Address $address, ?User $userModel = null): array
{
    // ✅ Kumpulkan semua kode yang mungkin dibutuhkan blade
    $villageCodes  = array_filter([$address?->village,  $userModel?->village]);
    $districtCodes = array_filter([$address?->district, $userModel?->district]);
    $cityCodes     = array_filter([$address?->city,     $userModel?->city]);
    $stateCodes    = array_filter([$address?->state,    $userModel?->state]);

    return [
        'villages'  => Village::whereIn('code',  $villageCodes  ?: [''])->get(),
        'districts' => District::whereIn('code', $districtCodes ?: [''])->get(),
        'cities'    => City::whereIn('code',     $cityCodes     ?: [''])->get(),
        'states'    => Province::whereIn('code', $stateCodes    ?: [''])->get(),
    ];
}

public function printvieworder($id)
{
    $this->guardAdmin('/my-orders');

    $order      = Order::findOrFail($id);
    $orderitems = OrderItem::where('order_id', $id)->get();

    // ✅ Kembalikan ke query builder (tidak ->first()) karena blade pakai ->value()
    $address = Address::where('order_id', $id);
    $user    = User::where('id', $order->user_id);

    $branch = Branch::select('logo', 'name_partner', 'name', 'phone')
        ->find($order->branch_id);

    $paymentlast = Payment::where('paymentable_type', Order::class)
        ->where('paymentable_id', $id)
        ->orderBy('date_payment', 'desc')
        ->get();

    // ✅ Geo: ambil kode dari query builder dulu
    $addressModel = $address->first();
    $userModel    = $user->first();

    return view('printorder', array_merge([
        'date'              => date('d/m/Y'),
        'order'             => $order,
        'orderitems'        => $orderitems,
        'address'           => $address,   // query builder untuk ->value()
        'paymentlast'       => $paymentlast,
        'user'              => $user,      // query builder untuk ->value()
        'branchLogo'        => $branch?->logo,
        'branchPartnerName' => $branch?->name_partner,
        'branchName'        => $branch?->name,
        'branchPhone'       => $branch?->phone,
    ], $this->geoData($addressModel, $userModel)));
}   

public function printvieworderprocess($id)
{
    $this->guardAdmin('/my-orders');

    $order      = Order::findOrFail($id);
    $orderitems = OrderItem::where('order_id', $id)->get();
    $address    = Address::where('order_id', $id);
    $user       = User::where('id', $order->user_id);

    $addressModel = $address->first();
    $userModel    = $user->first();

    return view('printorder-process', array_merge([
        'date'       => date('d/m/Y'),
        'order'      => $order,
        'orderitems' => $orderitems,
        'address'    => $address,
        'user'       => $user,
    ], $this->geoData($addressModel, $userModel)));
}

public function printviewtransferstock($id)
{
    $this->guardAdmin('/admin/tr-stk-outs');

    $trSTK      = TrStkOut::findOrFail($id);
    $orderitems = OrderItem::where('tr_stk_out_id', $id)->get();

    // ✅ Ambil hanya 2 branch yang relevan, tanpa filter is_active
    // supaya tidak error jika branch sudah nonaktif
    $branch = Branch::whereIn('id', [
        $trSTK->from_branch_id,
        $trSTK->to_branch_id,
    ])->get();

    return view('print-transfer-stock', [
        'date'       => date('d/m/Y'),
        'trSTK'      => $trSTK,
        'orderitems' => $orderitems,
        'branch'     => $branch,
    ]);
}
}