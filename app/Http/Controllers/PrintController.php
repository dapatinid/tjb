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
use Illuminate\Support\Str;

class PrintController extends Controller
{

    public function printvieworder($id)
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin == 0) {
                return redirect('/my-orders');
            }
        }

        $order = Order::find($id);
        $orderitems = OrderItem::where('order_id', $id)->get();
        $address = Address::where('order_id', $id)->take(1);
        $states = Province::all();
        $cities = City::all();
        $districts = District::all();
        $villages = Village::all();
        $paymentlast = Payment::orderBy('date_payment', 'desc')->get();
        $user = User::where('id', $order->user_id)->get();
        $branchLogo = Branch::where('id', $order->branch_id)->value('logo');
        $branchPartnerName = Branch::where('id', $order->branch_id)->value('name_partner');
        $branchName = Branch::where('id', $order->branch_id)->value('name');
        $branchPhone = Branch::where('id', $order->branch_id)->value('phone');
        $data = [
            'date' => date('d/m/Y'),
            'order' => $order,
            'orderitems' => $orderitems,
            'address' => $address,
            'states' => $states,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'paymentlast' => $paymentlast,
            'user' => $user,
            'branchLogo' => $branchLogo,
            'branchPartnerName' => $branchPartnerName,
            'branchName' => $branchName,
            'branchPhone' => $branchPhone,
        ];
        return view('printorder', $data);
    }
    public function printvieworderprocess($id)
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin == 0) {
                return redirect('/my-orders');
            }
        }

        $order = Order::find($id);
        $orderitems = OrderItem::where('order_id', $id)->get();
        $data = [
            'date' => date('d/m/Y'),
            'order' => $order,
            'orderitems' => $orderitems,
            'user' => User::where('id', $order->user_id)->get(),
            'address' => Address::where('order_id', $id)->take(1),
            'states' => Province::all(),
            'cities' => City::all(),
            'districts' => District::all(),
            'villages' => Village::all(),
        ];
        return view('printorder-process', $data);
    }
    public function printviewtransferstock($id)
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin == 0) {
                return redirect('/admin/tr-stk-outs');
            }
        }

        $trSTK = TrStkOut::find($id);
        $orderitems = OrderItem::where('tr_stk_out_id', $id)->get();
        $branch = Branch::where('is_active', 1)->get();
        $data = [
            'date' => date('d/m/Y'),
            'trSTK' => $trSTK,
            'orderitems' => $orderitems,
            'branch' => $branch,
        ];
        return view('print-transfer-stock', $data);
    }
}
