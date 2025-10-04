<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    $q = $request->query('q');
    $perPage = (int) $request->query('per_page', 10);


    $query = Product::query()->where('branch_id',Auth::user()->branch_id)->where('is_active',true);
    if ($q) {
    $query->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                    $query->orWhere('sku', 'LIKE', '%' . $q . '%');
                    $query->orWhere('variant', 'LIKE', '%' . $q . '%');
                    $query->orWhere('tags', 'LIKE', '%' . $q . '%');
                });
    }


    $products = $query->orderBy('name')->paginate($perPage)->appends($request->query());


    return response()->json($products);
    }


    public function show($id)
    {
    $product = Product::findOrFail($id);
    return response()->json($product);
    }
}