<?php

namespace App\Http\Controllers\Api;

// app/Http/Controllers/Api/ProductController.php

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()->where('branch_id',Auth::user()->branch_id)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10000); // Paginate 10000 produk per halaman

        return response()->json($products);
    }
}