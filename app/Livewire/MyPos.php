<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyPos extends Component
{

    public $cartItems = [];

    public function mount() {
        if (Auth::user()->is_admin == 0) {
            return redirect('/products');
        }
    }

    public function render()
    {

        $lastCart = Cart::where('branch_id', Auth::user()->branch_id)->where('created_by', Auth::user()->id)->get();
        // siapkan array sederhana yang mudah di-JSON-kan
        $initialCart = [];
        if ($lastCart) {
            $initialCart = $lastCart->map(function($it) {
                    return [
                        'id' => $it->product_id,
                        'name' => $it->name ?? '',
                        'variant' => $it->variant ?? '',
                        'weight' => (float) $it->total_weight / $it->quantity ?? '',
                        'quantity' => (int) $it->quantity,
                        'price' => (float) $it->unit_amount,
                        'subtotal' => (float) $it->total_amount,
                        'subtotalweight' => (float) $it->total_weight,
                    ];
            })->toArray();
        }
        Cart::where('branch_id', Auth::user()->branch_id)->where('created_by', Auth::user()->id)->delete();
        $categories = Category::select('id', 'name')->where('is_active',true)->get();
        return view('livewire.my-pos', compact('initialCart', 'categories'));
    }

        public function triggerLoadCart()
    {
        $this->dispatch('loadCart'); // trigger ke JS
    }

    public function saveCart($data)
    {
        $this->cartItems = $data;
        foreach ($this->cartItems as $item) {
            $cart = new Cart();
            $cart->product_id = $item['id'];
            $cart->name = $item['name'];
            $cart->variant = $item['variant'];
            $cart->slug = Product::find($item['id'])->slug;
            $cart->unit_name = Product::find($item['id'])->unit_name;
            // $cart->total_weight = $item['subtotalweight'];
            $cart->contain = Product::find($item['id'])->contain;
            $cart->image = Product::find($item['id'])->images[0] ?? null;
            $cart->quantity = $item['quantity'];
            $cart->unit_amount = $item['price'];
            $cart->total_amount = $item['subtotal'];
            // $cart->poin = Product::find($item['id'])->poin * $item['quantity'];
            $cart->mutation_type = 'Sales';
            $cart->created_by = Auth::user()->id;
            $cart->updated_by = Auth::user()->id;
            $cart->branch_id = Auth::user()->branch_id;
            $cart->save();
        }
        $this->cartItems = [];
        $this->redirect("/checkout?branch_id=" . Auth::user()->branch_id , navigate: false);
        // $this->redirect("/checkout?branch_id=" . Auth::user()->branch_id . "&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR&date_order=" . date('Y') . "-" . date('m') . "-" . date('d') . "T" . date('H') . "%3A" . date('i'), navigate: true);
    }

}
