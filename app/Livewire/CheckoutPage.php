<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
// use App\Livewire\Partials\Navbar;
// use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
// use App\Models\Partner;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;
// use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

#[Title('Checkout - TegarJaya')]
class CheckoutPage extends Component
{
    #[Url()]
    public $sales_type;
    #[Url()]
    public $branch_id;
    #[Url()]
    public $user_id;
    #[Url()]
    public $date_order;
    #[Url()]
    public $shipping_method;

    public $discount = 0;
    public $shipping_amount = 0;
    public $total_payment = 0;
    #[Url()]
    public $payment_method;
    #[Url()]
    public $rekening;
    public $notes;

    public $first_name;
    public $last_name;

    public $phone;

    #[Url()]
    public $street_address;
    #[Url()]
    public $village;
    #[Url()]
    public $district;
    #[Url()]
    public $city;
    #[Url()]
    public $state;
    #[Url()]
    public $zip_code;


    public function mount()
    {
        $cartitems = CartManagement::getCartItemsFromCart();
        if (count($cartitems) == 0) {
            return redirect('/products');
        }

        if (Auth::user()->is_admin == 0 && Auth::user()->phone != '') {
            $this->phone = Auth::user()->phone;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->street_address != '') {
            $this->street_address = Auth::user()->street_address;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->village != '') {
            $this->village = Auth::user()->village;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->district != '') {
            $this->district = Auth::user()->district;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->city != '') {
            $this->city = Auth::user()->city;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->state != '') {
            $this->state = Auth::user()->state;
        }
        if (Auth::user()->is_admin == 0 && Auth::user()->zip_code != '') {
            $this->zip_code = Auth::user()->zip_code;
        }
    }

    public function selectUser()
    {
        $this->state = User::find($this->user_id)->state;
        $this->zip_code = User::find($this->user_id)->zip_code;
        $this->city = User::find($this->user_id)->city;
        $this->street_address = User::find($this->user_id)->street_address;
        $this->district = User::find($this->user_id)->district;
        $this->phone = User::find($this->user_id)->phone;
        $this->village = User::find($this->user_id)->village;
    }

    public function selectServis()
    {
        if ($this->shipping_method == 'dine_in') {
            $this->sales_type = 'dine_in';
        } elseif ($this->shipping_method == 'self_pickup') {
            $this->sales_type = 'self_pickup';
        } elseif ($this->shipping_method != 'dine_in' || $this->shipping_method != 'self_pickup') {
            $this->sales_type = 'delivery';
        } else {
            $this->sales_type = '';
        }

        $this->validate([
            'sales_type' => 'required',
        ]);
    }

    public function placeOrder()
    {

        $isadmin = Auth::user()->is_admin;
        $today = Carbon::today()->format('Y-m-d');

        if ($isadmin == 1) {
            $cekPartnerID = Branch::where('id', $this->branch_id)->value('partner_id');
            if ($cekPartnerID == Auth::user()->partner_id) {
                $this->branch_id = $this->branch_id;
            } else {
                $this->branch_id = '';
            }
        }


        $this->validate([
            'sales_type' => 'required',
            'payment_method' => 'required',
            'branch_id' => 'required',
        ]);

        if ($isadmin == 1) {
            if ($this->sales_type == 'self_pickup' || $this->sales_type == 'delivery') {
                $this->validate([
                    'user_id' => 'required',
                    'rekening' => 'required',
                    'discount' => 'required|min:0',
                    'shipping_method' => 'required',
                    'shipping_amount' => 'required|min:0',
                    'total_payment' => 'required|min:0',
                    'date_order' => 'required',
                ]);
            }
            if ($this->sales_type == 'dine_in') {
                $this->validate([
                    'user_id' => 'required',
                    'rekening' => 'required',
                    'discount' => 'required|min:0',
                    'shipping_method' => 'required',
                    'shipping_amount' => 'required|min:0',
                    'total_payment' => 'required|min:0',
                    'date_order' => 'required',
                ]);
            }
        }

        if ($isadmin == 0) {

            if ($this->sales_type == 'delivery') {
                $this->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                    'street_address' => 'required',
                    'village' => 'required',
                    'district' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    // 'zip_code' => 'required',
                ]);
            }

            if ($this->sales_type == 'self_pickup') {
                $this->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                ]);
            }
            if ($this->sales_type == 'dine_in') {
                $this->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                ]);
            }
        }


        $cart_items = CartManagement::getCartItemsFromCart()->where('branch_id', $this->branch_id);

        $order = new Order();
        $order->q = Order::where('branch_id', Auth::user()->branch_id)->where('date_order', 'like', "%$today%")->count() + 1;
        $order->branch_id = $this->branch_id;
        $order->code_tr = 'ORD' . date('YmdHis') . '-' . Auth::user()->id . '-' . Order::where('branch_id', Auth::user()->branch_id)->where('created_by', Auth::user()->id)->where('date_order', 'like', "%" . Carbon::now()->format('Y-m-d') . "%")->count() + 1;
        $order->created_by = Auth::user()->id;
        $order->updated_by = Auth::user()->id;
        $order->total_weight = Cart::where('created_by', Auth::user()->id)->where('branch_id', $this->branch_id)->sum('total_weight');
        $order->grand_total = Cart::where('created_by', Auth::user()->id)->where('branch_id', $this->branch_id)->sum('total_amount') - Str::replace('.', '', $this->discount) + Str::replace('.', '', $this->shipping_amount);
        $order->sales_type = $this->sales_type;
        if (Str::replace('.', '', $this->total_payment) >= $order->grand_total) {
            $order->is_paid = 1;
            $order->paid_at = date('Y-m-d H:i:s');
        } else {
            $order->is_paid = 0;
            $order->paid_at = null;
        }
        if ($isadmin == 1) {
            $order->date_order = $this->date_order;
            $order->status = 'processing';
        } else {
            $order->date_order = date('Y-m-d H:i:s');
            $order->status = 'new';
        }
        if (isset($this->notes)) {
            // $order->notes =  $this->notes . PHP_EOL . PHP_EOL . 'Order oleh ' . PHP_EOL . Auth::user()->name . ' <' . Auth::user()->email . '>';
            $order->notes =  $this->notes . PHP_EOL .  'Order oleh ' . PHP_EOL . Auth::user()->name;
            // PHP_EOL untuk enter textarea
        }
        // else {
        // $order->notes =  'Order oleh ' . PHP_EOL . Auth::user()->name . ' <' . Auth::user()->email . '>';
        // }

        if ($isadmin == 1) {
            $order->user_id = $this->user_id;
            $order->shipping_method = $this->shipping_method;
            $order->shipping_amount = Str::replace('.', '', $this->shipping_amount);
            $order->discount = Str::replace('.', '', $this->discount);
            if ($this->payment_method === 'transfer' && Str::replace('.', '', $this->total_payment) >= $order->grand_total) {
                $order->total_payment = $order->grand_total;
            } else {
                $order->total_payment = Str::replace('.', '', $this->total_payment);
            }
            $order->total_cashback = $order->total_payment - $order->grand_total;
        } else {
            $order->user_id = Auth::user()->id;
            $order->shipping_method = 'kurir_taibah';
            $order->shipping_amount = 0;
            $order->discount = 0;
            $order->total_payment = 0;
            $order->total_cashback = $order->total_payment - $order->grand_total;
        }

        $payment = new Payment();
        $payment->date_payment = date('Y-m-d H:i:s');
        $payment->currency = 'idr';
        $payment->payment_method = $this->payment_method;
        if ($this->payment_method === 'transfer' && Str::replace('.', '', $this->total_payment) >= $order->grand_total) {
            $payment->nominal_plus = $order->grand_total;
            $payment->nominal_mins = 0;
            $payment->nominal = $order->grand_total;
        } else {
            if (Str::replace('.', '', $this->total_payment) >= $order->grand_total) {
                $payment->nominal_plus = Str::replace('.', '', $this->total_payment);
                $payment->nominal_mins = $payment->nominal_plus - $order->grand_total;
                $payment->nominal = $payment->nominal_plus - $payment->nominal_mins;
            } else {
                $payment->nominal_plus = Str::replace('.', '', $this->total_payment);
                $payment->nominal_mins = 0;
                $payment->nominal = $payment->nominal_plus;
            }
        }
        $payment->mutation_type = 'Sales';
        $payment->debit = 'NR-DB-B-1100 CASH / BANK';
        $payment->kredit = 'NR-DB-B-3000 Piutang Penjualan Barang';
        if ($isadmin == 0) {
            $payment->rekening = 'KAS KASIR';
        } else {
            $payment->rekening = $this->rekening;
        }
        $payment->created_by = Auth::user()->id;
        $payment->updated_by = Auth::user()->id;
        $payment->branch_id = $this->branch_id;


        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->village = $this->village;
        $address->district = $this->district;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $redirect_url = '';

        if ($this->payment_method == 'stripe') {
            $redirect_url = route('success');
        } else {
            $redirect_url = route('success');
        }

        $order->save();
        $payment->user_id = $order->user_id;
        $payment->paymentable_id = $order->id;
        $payment->paymentable_type = 'App\Models\Order';
        $payment->save();
        $address->order_id = $order->id;
        $address->save();

        foreach ($cart_items as $item) {
            $order->items()->saveMany([
                new OrderItem([
                    'product_id' => $item['product_id'],
                    'unit_name' => $item['unit_name'],
                    'total_weight' => $item['total_weight'],
                    'contain' => $item['contain'],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $item['unit_amount'],
                    'total_amount' => $item['total_amount'],
                    'poin' => $item['poin'],
                    'status' => $order->status,
                    'mutation_type' => 'Sales',
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'branch_id' => $this->branch_id,
                ]),
            ]);
        }

        // hitung Piutang Penjualan untuk jurnal
        $piutang = new Payment();
        $piutang->paymentable_id = $order->id;
        $piutang->paymentable_type = 'App\Models\Order';
        $piutang->mutation_type = 'Piutang Penjualan';
        $piutang->date_payment = date('Y-m-d H:i:s');
        $piutang->currency = 'idr';
        $piutang->debit = 'NR-DB-B-3000 Piutang Penjualan Barang';
        $piutang->kredit = 'LR-KR-E-1000 Pendapatan Penjualan Kasir';
        $piutang->nominal_plus = $order->grand_total;
        $piutang->nominal_mins = 0;
        $piutang->nominal = $order->grand_total;
        $piutang->user_id = $order->user_id;
        $piutang->created_by = Auth::user()->id;
        $piutang->updated_by = Auth::user()->id;
        $piutang->branch_id  = Auth::user()->branch_id;
        $piutang->save();

        // hitung nilai barang untuk jurnal
        $barang_terjual = 0;
        foreach ($cart_items as $citem) {
            $barang_terjual += Product::where('id', $citem->product_id)->value('cogs') * $citem->quantity;
        }

        $barangterjual = new Payment();
        $barangterjual->paymentable_id = $order->id;
        $barangterjual->paymentable_type = 'App\Models\Order';
        $barangterjual->mutation_type = 'Barang Terjual';
        $barangterjual->date_payment = date('Y-m-d H:i:s');
        $barangterjual->currency = 'idr';
        $barangterjual->debit = 'LR-DB-F-1100 Barang Terjual';
        $barangterjual->kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
        $barangterjual->nominal_plus = 0;
        $barangterjual->nominal_mins = $barang_terjual;
        $barangterjual->nominal = $barang_terjual;
        $barangterjual->user_id = $order->user_id;
        $barangterjual->created_by = Auth::user()->id;
        $barangterjual->updated_by = Auth::user()->id;
        $barangterjual->branch_id  = Auth::user()->branch_id;
        $barangterjual->save();


        // RESET FORM dan CART lalu REDIRECT
        $this->user_id = '';
        $this->payment_method = '';
        CartManagement::clearCartItemsOnBranch($this->branch_id);

        //email notif
        // if ($isadmin == 0) {
        // Mail::to(request()->user())->send(new OrderPlaced($order));
        // Mail::to('mangunwirayuda@gmail.com')->send(new OrderPlaced($order));
        // }

        return $this->redirect($redirect_url, navigate: true);
    }

    public function refreshAddress()
    {
        $this->dispatch('checkout-page');
    }
    public function removeItem($product_id)
    {
        CartManagement::removeCartItem($product_id);
        $this->dispatch('$refresh');
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
    public function changeStore()
    {
        $update = [
            'branch_id' => $this->branch_id,
            'partner_id' => Branch::find($this->branch_id)->partner_id,
        ];
        User::where('id', Auth::user()->id)->update($update);
    }
    public function change_payment_method()
    {
        $this->rekening = "";
    }


    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCart()->where('branch_id', $this->branch_id);
        $subtotal = Cart::where('created_by', Auth::user()->id)->where('branch_id', $this->branch_id)->sum('total_amount');
        $discount = intval(Str::replace('.', '', $this->discount));
        $shipping_amount = intval(Str::replace('.', '', $this->shipping_amount));
        $grand_total = $subtotal - $discount + $shipping_amount;
        $total_payment =  intval(Str::replace('.', '', $this->total_payment));
        $total_cashback = $total_payment - $grand_total;
        $states = Province::all();
        $cities = City::all()->where('province_code', $this->state)->sortByDesc('name');
        $districts = District::all()->where('city_code', $this->city)->sortBy('name');
        $villages = Village::all()->where('district_code', $this->district)->sortBy('name');

        $branchesCust = Cart::where('created_by', Auth::user()->id)->groupBy('branch_id')->selectRaw('branch_id')->get();
        $users = User::all();

        if (Auth::user()->is_admin == 0) {
            $branches = Branch::all()->where('is_active', 1);
        } else {
            $branches = Branch::all()->where('partner_id', Auth::user()->partner_id)->where('is_active', 1);
        }
        $products = Product::all();

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'subtotal' => $subtotal,
            'grand_total' => $grand_total,
            'states' => $states,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
            'users' => $users,
            'branchesCust' => $branchesCust,
            'branches' => $branches,
            'discount' => $discount,
            'shipping_amount' => $shipping_amount,
            'total_cashback' => $total_cashback,
            'total_payment' => $total_payment,
            'products' => $products,
        ]);
    }
}
