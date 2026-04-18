<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - TegarJaya')]
class ProductDetailPage extends Component
{

    public $slug;
    public $name;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;

        $cart_items = CartManagement::getCartItemsFromCart();
        foreach ($cart_items as $item) {
            $quantitythis = $item['quantity'];
            if ($item['slug'] == $this->slug && $quantitythis > 0) {
                return $this->quantity = $item['quantity'];
            } else {
                $this->quantity = 1;
            }
        }
    }

    // public function increaseQty()
    // {
    //     $this->quantity++;
    // }
    // public function decreaseQty()
    // {
    //     if ($this->quantity > 1) {
    //         $this->quantity--;
    //     }
    // }

    // add product to cart method 
    public function addToCart($product_id)
    {
        if ($this->quantity < 1) {
            LivewireAlert::title('Minimal Qty adalah 1')
                ->warning()
                ->toast()
                ->position('center')
                ->show();
        } else {
            if (Auth::check()) {
                CartManagement::addItemToCartWithQty($product_id, $this->quantity);
                $this->dispatch('update-cart-count', total_count: count(CartManagement::getCartItemsFromCart()))->to(Navbar::class);

                LivewireAlert::title('Item Saved')
                    ->text('Ditambahkan di Troli.')
                    ->success()
                    ->toast()
                    ->position('center')
                    ->show();

                // return $this->redirect(url()->previous(3), navigate: true);

            } else {

                redirect('/login');
            }
        }
    }

        public function render()
        {
            // ✅ Satu query untuk product
            $product = Product::where('slug', $this->slug)->firstOrFail();

            // ✅ Satu query untuk branch
            $branch = Branch::find($product->branch_id);

            // ✅ Variants dari data yang sudah ada
            $variants = Product::where('branch_id', $product->branch_id)
                ->where('is_active', 1)
                ->where('name', $product->name)
                ->get();

            $mitra = Partner::where('id', $branch->partner_id)->value('slug');

            return view('livewire.product-detail-page', [
                'product'   => $product,
                'orderitem' => collect(), // sudah tidak dipakai di blade
                'variants'  => $variants,
                'branch'    => $branch->name,
                'mitra'     => $mitra,
            ]);
        }
}
