<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        if (Auth::check()) {
            $produkbyBRAND = Product::where('branch_id', auth()->user()->branch_id)
                ->inRandomOrder()->get()
                ->groupBy('brand_id')->take(6);
            $produkbyCATE = Product::where('branch_id', auth()->user()->branch_id)
                ->inRandomOrder()->get()
                ->groupBy('category_id')->take(12);
            $brands = Brand::where('is_active', 1)->get();
            $categories = Category::where('is_active', 1)->get();
        } else {
            $produkbyBRAND = Product::all();
            $produkbyCATE = Product::all();
            $brands = Brand::where('is_active', 1)->inRandomOrder()->get()->take(6);
            $categories = Category::where('is_active', 1)->inRandomOrder()->get()->take(12);
        }
        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
            'produkbyBRAND' => $produkbyBRAND,
            'produkbyCATE' => $produkbyCATE,
        ]);
    }
}
