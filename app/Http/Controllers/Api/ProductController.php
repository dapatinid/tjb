<?php

namespace App\Http\Controllers\Api;

// app/Http/Controllers/Api/ProductController.php

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('q');
        $products = Product::where('branch_id',Auth::user()->branch_id)
        ->when($search, fn($q) => 
            $q->where('name','like',"%{$search}%")
        )
        ->paginate(5)->withQueryString();

        return view('cartalpine', compact('products','search'));
    }
}