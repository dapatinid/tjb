<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\JumboTron;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categories & Brands - TegarJaya')]

class CategoriesPage extends Component
{
    public $cariCat = '';
    public $cariBrn = '';

    public function render()
    {
        $jumbotrons = JumboTron::query()->where('is_active', 1)->where('target', 'category')->get();
        $categoryQuery = Category::where('is_active', 1);
        $brandQuery = Brand::query()->where('is_active', 1);
        $productcek = Product::all();

        return view('livewire.categories-page', [
            'jumbotrons' => $jumbotrons,
            'categories' => $categoryQuery,
            'brands' => $brandQuery,
            'productcek' => $productcek,
            'cariCat' => $this->cariCat,
            'cariBrn' => $this->cariBrn
        ]);
    }
}
