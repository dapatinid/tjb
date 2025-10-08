<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - TegarJaya')]

class ProductsPage extends Component
{
    use WithPagination;

    #[Url()]
    public $selected_categories = [];

    #[Url()]
    public $selected_brands = [];

    #[Url()]
    public $featured = [];

    #[Url()]
    public $promo = [];

    #[Url()]
    public $price_range = 10000000;

    public $sort = 'latest';

    #[Url()]
    public $cari = '';

    #[Url()]
    public $branch;

    #[Url()]
    public $page = '';

    public function mount()
    {
        if (Auth::check()) {
            if (Auth::user()->branch != '') {
                $this->branch = Auth::user()->branch_id;
            } else {
                $this->branch = '';
            }
        }
    }

    // add product to cart method
    public function addToCart($product_id)
    {
        if (Auth::check()) {
            CartManagement::addItemToCart($product_id);
            $this->dispatch('update-cart-count', total_count: count(CartManagement::getCartItemsFromCart()))->to(Navbar::class);
            // $this->dispatch('products-page');

            LivewireAlert::title('Ditambahkan ke Troli')
                // ->text('Ditambahkan ke Troli')
                ->success()
                ->toast()
                ->position('top-end')
                ->timer(3000)
                ->show();
        } else {
            redirect('/login');
        }
    }

    // change Branch in User
    // public function changeBranch()
    // {
    //     if (Auth::check()) {
    //         $data = User::where('id', Auth::user()->id);

    //         if (Auth::user()->is_admin == 1) {
    //             $update = [
    //                 'branch_id' => Auth::user()->branch_id,
    //             ];
    //         } else {
    //             $update = [
    //                 'branch_id' => $this->branch,
    //             ];
    //         }
    //         $data->update($update);
    //         return redirect('/products');
    //     } else {
    //         return redirect('/branches');
    //     }
    // }

    public function render()
    {
        if (Auth::check()) {
            $isadmin = Auth::user()->is_admin;
        } else {
            $isadmin = 0;
        }

        // $orderitems = OrderItem::all();

        if ($isadmin == 1) {
            $productQuery = Product::query();
        } else {
            $productQuery = Product::query()->where('is_active', 1);
        }

        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }
        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }
        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }
        if ($this->promo) {
            $productQuery->where('promo', 1);
        }
        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }
        if ($this->sort == 'latest') {
            $productQuery->latest();
        }
        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        if (empty($this->branch)) {
            $productQuery;
        } else {
            $productQuery->where('branch_id', $this->branch);
        }

        if (!empty($this->cari)) {
            $pencarian = $this->cari;
            $productQuery
                ->where(function ($query) use ($pencarian) {
                    $query->where('name', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('sku', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('variant', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('description', 'LIKE', '%' . $pencarian . '%');
                    $query->orWhere('tags', 'LIKE', '%' . $pencarian . '%');
                });
        }

        if ($this->page == "" && $this->cari == null && $this->selected_categories == null && $this->selected_brands == null && $this->featured == null && $this->promo == null && $this->price_range == 10000000) {
            $url = 0;
        } else {
            $url = 1;
        }

        $productcek = Product::all();

        if (Auth::check()) {
            if (Auth::user()->is_admin == 0) {
                $branches = Branch::all()->where('is_active', 1);
            } else {
                $branches = Branch::all()->where('partner_id', Auth::user()->partner_id)->where('is_active', 1);
            }
        } else {
            $branches = Branch::all()->where('is_active', 1);
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(24)->withQueryString(),
            // 'orderitem' => $orderitems,
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
            'branches' => $branches,
            'url' => $url,
            'productcek' => $productcek,
        ]);
    }
}
