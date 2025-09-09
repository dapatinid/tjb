<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PosCart extends Component
{
    public $products = [];
    public $cartItems = [];
    public $search = '';

    public function mount()
    {
        // $this->products = [
        //     ['id' => 1, 'name' => 'Kopi Latte', 'price' => 25000],
        //     ['id' => 2, 'name' => 'Cappuccino', 'price' => 28000],
        //     ['id' => 3, 'name' => 'Espresso', 'price' => 20000],
        //     ['id' => 4, 'name' => 'Croissant', 'price' => 15000],
        //     ['id' => 5, 'name' => 'Red Velvet Cake', 'price' => 35000],
        // ];

        $this->products = Product::where('branch_id', Auth::user()->branch_id)->get(['id', 'name', 'variant', 'slug', 'unit_name', 'weight', 'contain', 'price', 'poin', 'images', 'branch_id']);

        $this->loadCartFromLocalStorage();
    }

    public function loadCartFromLocalStorage()
    {
        $this->dispatch('loadCart');
    }

    public function addToCart($productId)
    {
        $product = collect($this->products)->firstWhere('id', $productId);

        if ($product) {
            $existingItem = collect($this->cartItems)->firstWhere('id', $productId);

            if ($existingItem) {
                $this->cartItems = collect($this->cartItems)->map(function ($item) use ($productId) {
                    if ($item['id'] == $productId) {
                        $item['quantity']++;
                        $item['subtotal'] = $item['quantity'] * $item['price'];
                        $item['weighttotal'] = $item['quantity'] * $item['weight'];
                    }
                    return $item;
                })->toArray();
            } else {
                $item = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'variant' => $product['variant'],
                    'price' => $product['price'],
                    'weight' => $product['weight'],
                    'quantity' => 1,
                    'subtotal' => $product['price'],
                    'weighttotal' => $product['weight'],
                ];
                array_push($this->cartItems, $item);
            }

            $this->saveCartToLocalStorage();
        }
    }

    public function updateCartItem($itemId, $field, $value)
    {
        $this->cartItems = collect($this->cartItems)->map(function ($item) use ($itemId, $field, $value) {
            if ($item['id'] == $itemId) {
                $item[$field] = (int) $value;
                $item['subtotal'] = $item['quantity'] * $item['price'];
                $item['weighttotal'] = $item['quantity'] * $item['weight'];
            }
            return $item;
        })->toArray();

        $this->saveCartToLocalStorage();
    }

    public function removeItem($itemId)
    {
        $this->cartItems = collect($this->cartItems)->reject(function ($item) use ($itemId) {
            return $item['id'] == $itemId;
        })->values()->toArray();

        $this->saveCartToLocalStorage();
    }

    public function saveCartToLocalStorage()
    {
        $this->dispatch('saveCart', items: $this->cartItems);
    }

    public function getFilteredProductsProperty()
    {
        return collect($this->products)->filter(function ($product) {
            return stripos($product['name'], $this->search) !== false;
        })->values();
    }

    public function getTotalProperty()
    {
        return collect($this->cartItems)->sum('subtotal');
    }
    public function getWeighttotalProperty()
    {
        return collect($this->cartItems)->sum('weighttotal');
    }

    public function refreshPage()
    {
        // $this->dispatch('pos-page');
        $this->redirect('/poscart', navigate: true);
    }
    public function saveCart()
    {
        foreach ($this->cartItems as $item) {
            $cart = new Cart();
            $cart->product_id = $item['id'];
            $cart->name = $item['name'];
            $cart->variant = $item['variant'];
            $cart->slug = Product::find($item['id'])->slug;
            $cart->unit_name = Product::find($item['id'])->unit_name;
            $cart->total_weight = $item['weighttotal'];
            $cart->contain = Product::find($item['id'])->contain;
            $cart->image = Product::find($item['id'])->images[0] ?? null;
            $cart->quantity = $item['quantity'];
            $cart->unit_amount = $item['price'];
            $cart->total_amount = $item['subtotal'];
            $cart->poin = Product::find($item['id'])->poin;
            $cart->mutation_type = 'Sales';
            $cart->created_by = Auth::user()->id;
            $cart->updated_by = Auth::user()->id;
            $cart->branch_id = Auth::user()->branch_id;
            $cart->save();
        }
        $this->redirect("/checkout?branch_id=" . Auth::user()->branch_id . "&shipping_method=self_pickup&sales_type=self_pickup&payment_method=cash&rekening=KAS+KASIR&date_order=" . date('Y') . "-" . date('m') . "-" . date('d') . "T" . date('H') . "%3A" . date('i'), navigate: true);
    }
}
