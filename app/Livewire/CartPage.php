<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - TegarJaya')]
class CartPage extends Component
{

    public $cart_items = [];
    public $grand_total;
    public $quantity = [];
    public $thisqty;

    public function mount()
    {
        if (Auth::check()) {
            $this->cart_items = CartManagement::getCartItemsFromCart();
            $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        }
        // else {
        //     return redirect('/login');
        // }
    }

    #[On('update-cartItemsList')]
    public function updateCartList()
    {
        $this->cart_items = CartManagement::getCartItemsFromCart();
    }

    public function clearItem()
    {
        LivewireAlert::title('Kosongkan Semua Troli?')
            ->withConfirmButton('YA')
            ->withCancelButton('Batal')
            ->onConfirm('clearItemYes')
            ->show();
    }

    public function clearItemYes()
    {
        $this->cart_items = CartManagement::clearCartItems();
        $this->cart_items = CartManagement::getCartItemsFromCart();
    }

    public function clearItemByBranch($branch_id, $qty_cart)
    {
        LivewireAlert::title('Hapus ' . $qty_cart . ' item ini?')
            ->withConfirmButton('YA')
            ->withCancelButton('Batal')
            ->onConfirm('clearItemByBranchYes')
            ->show();
    }

    public function clearItemByBranchYes($branch_id)
    {
        $this->cart_items = CartManagement::clearCartItemsOnBranch($branch_id);
        $this->cart_items = CartManagement::getCartItemsFromCart();
    }

    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-quantity', total_count: count($this->cart_items))->to(Navbar::class);
        // $this->dispatch('cart-page');
        $this->dispatch('update-cartItemsList');
    }

    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // $this->dispatch('cart-page');
        $this->dispatch('update-cartItemsList');
    }
    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // $this->dispatch('cart-page');
        $this->dispatch('update-cartItemsList');
    }

    public function typeQty($id, $qty)
    {
        if ($this->thisqty == null || $this->thisqty == '') {
            $set_qty = $qty;
        } else {
            if ($this->thisqty <= 1) {
                $set_qty = 1;
            } else {
                $set_qty = $this->thisqty;
            }
        }
        $this->cart_items = CartManagement::typingQuantityToCartItem($id, $set_qty);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // $this->dispatch('cart-page');
        $this->dispatch('update-cartItemsList');
        $this->thisqty = '';
    }
    public function typeQtyReset()
    {
        $this->thisqty = '';
    }

    public function render()
    {
        if (Auth::check()) {
            $grup_items = Cart::where('created_by', auth()->user()->id)
                ->groupBy('branch_id')
                ->selectRaw('count(*) as total, branch_id')
                ->get();
        } else {
            $grup_items = Cart::all();
        }

        $branches = Branch::all();
        $mitra = Partner::all();
        return view('livewire.cart-page', [
            'mitra' => $mitra,
            'branches' => $branches,
            'grup_items' => $grup_items,
            'cart_items' => $this->cart_items,
        ]);
    }
}
