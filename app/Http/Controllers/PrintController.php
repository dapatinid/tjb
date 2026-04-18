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
    private function geoData(?Address $address): array
    {
        return [
            'villages'  => Village::whereIn('code',  [$address->village  ?? ''])->get(),
            'districts' => District::whereIn('code', [$address->district ?? ''])->get(),
            'cities'    => City::whereIn('code',     [$address->city     ?? ''])->get(),
            'states'    => Province::whereIn('code', [$address->state    ?? ''])->get(),
        ];
    }

    public function printvieworder($id)
    {
        $this->guardAdmin('/my-orders');

        $order      = Order::findOrFail($id);
        $orderitems = OrderItem::where('order_id', $id)->get();
        $address    = Address::where('order_id', $id)->first();

        // ✅ Satu query untuk branch
        $branch = Branch::select('logo', 'name_partner', 'name', 'phone')
            ->find($order->branch_id);

        // ✅ Hanya payment untuk order ini
        $paymentlast = Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $id)
            ->orderBy('date_payment', 'desc')
            ->get();

        $user = User::where('id', $order->user_id)->first();

        return view('printorder', array_merge([
            'date'               => date('d/m/Y'),
            'order'              => $order,
            'orderitems'         => $orderitems,
            'address'            => $address,
            'paymentlast'        => $paymentlast,
            'user'               => $user,
            'branchLogo'         => $branch?->logo,
            'branchPartnerName'  => $branch?->name_partner,
            'branchName'         => $branch?->name,
            'branchPhone'        => $branch?->phone,
        ], $this->geoData($address)));
    }

    public function printvieworderprocess($id)
    {
        $this->guardAdmin('/my-orders');

        $order      = Order::findOrFail($id);
        $orderitems = OrderItem::where('order_id', $id)->get();
        $address    = Address::where('order_id', $id)->first();
        $user       = User::where('id', $order->user_id)->first();

        return view('printorder-process', array_merge([
            'date'       => date('d/m/Y'),
            'order'      => $order,
            'orderitems' => $orderitems,
            'address'    => $address,
            'user'       => $user,
        ], $this->geoData($address)));
    }

    public function printviewtransferstock($id)
    {
        $this->guardAdmin('/admin/tr-stk-outs');

        $trSTK      = TrStkOut::findOrFail($id);
        $orderitems = OrderItem::where('tr_stk_out_id', $id)->get();
        $branch     = Branch::where('is_active', 1)->get();

        return view('print-transfer-stock', [
            'date'       => date('d/m/Y'),
            'trSTK'      => $trSTK,
            'orderitems' => $orderitems,
            'branch'     => $branch,
        ]);
    }
}