<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Order Detail - TegarJaya')]
class MyOrderDetailPage extends Component
{
    public $order_id; // ganti nama agar tidak bentrok dengan $order di render

    public $payment_date_paid;
    public $payment_method;
    public $rekening;
    public $input_payment;

    public $payment_date_paid_edit;
    public $payment_method_edit;
    public $rekening_edit;
    public $input_payment_edit;

    public $qty_return;

    public function mount($id) // ✅ gunakan $id sesuai route /my-orders/{id}
    {
        // ✅ Satu query saja, tidak dua kali
        $order = Order::findOrFail($id);

        if (Auth::user()->is_admin == 1) {
            if ($order->branch_id != Auth::user()->branch_id) {
                return $this->redirect('/my-orders', navigate: true);
            }
        } else {
            if (Auth::id() != $order->user_id) {
                return $this->redirect('/my-orders', navigate: true);
            }
        }

        $this->order_id = $id;
    }

    public function render()
    {
        $order = Order::with('branch')->findOrFail($this->order_id);

        $order_items = OrderItem::with('product')
            ->where('order_id', $this->order_id)
            ->get();

        $address = Address::where('order_id', $this->order_id)->first();

        $paymentslast = Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $this->order_id)
            ->latest()
            ->first();

        // ✅ Hanya ambil payment untuk order ini, bukan Payment::all()
        $payments = Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $this->order_id)
            ->where('mutation_type', 'Sales')
            ->latest()
            ->get();

        // ✅ Ambil data geografis hanya yang relevan dengan address ini
        $address_codes = $address
            ? [$address->village, $address->district, $address->city, $address->state]
            : [];

        $villages   = Village::whereIn('code', [$address->village ?? ''])->get();
        $districts  = District::whereIn('code', [$address->district ?? ''])->get();
        $cities     = City::whereIn('code', [$address->city ?? ''])->get();
        $states     = Province::whereIn('code', [$address->state ?? ''])->get();

        // ✅ Ambil user yang benar-benar dibutuhkan saja
        $paymentUserIds = $payments->pluck('updated_by')->filter()->unique();
        $user = User::whereIn('id', $paymentUserIds->merge([$order->user_id]))->get();

        return view('livewire.my-order-detail-page', [
            'order'              => $order,
            'order_items'        => $order_items,
            'address'            => $address,
            'address_firstname'  => $address?->first_name,
            'address_lastname'   => $address?->last_name,
            'paymentslast'       => $paymentslast,
            'payments'           => $payments,
            'user'               => $user,
            'states'             => $states,
            'cities'             => $cities,
            'districts'          => $districts,
            'villages'           => $villages,
        ]);
    }
}