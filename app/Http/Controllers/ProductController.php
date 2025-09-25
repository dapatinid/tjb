<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = Product::where('branch_id',Auth::user()->branch_id)->where('is_active',true)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->paginate(24); // pagination 24 produk per halaman

        if ($request->wantsJson()) {
            return response()->json($products);
        }

        return view('products.index', compact('products'));
    }
}