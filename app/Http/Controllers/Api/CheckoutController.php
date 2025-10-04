<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CheckoutController extends Controller 
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.variant' => 'nullable|string|max:255',
            'items.*.weight' => 'nullable|numeric|min:0',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $items = $data['items'];

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($items as $it) {
                $total += round($it['quantity'] * $it['price'], 2);
            }
            $totalcount = count($items);

            // $cart = Cart::create([
            //     'customer_name' => $data['customer_name'] ?? null,
            //     'total' => $total,
            // ]);


            foreach ($items as $it) {
                Cart::create([
                    // 'cart_id' => $cart->id,

                    'product_id' => $it['id'],
                    'name' => $it['name'],
                    'variant' => $it['variant'],
                    'slug' => Product::find($it['id'])->slug,
                    'unit_name' => Product::find($it['id'])->unit_name,
                    'total_weight' => $it['weight'] * $it['quantity'],
                    'contain' => Product::find($it['id'])->contain,
                    'image' => Product::find($it['id'])->images[0] ?? null,
                    'quantity' => $it['quantity'],
                    'unit_amount' => $it['price'],
                    'total_amount' => round($it['quantity'] * $it['price'], 2),
                    'poin' => Product::find($it['id'])->poin * $it['quantity'],
                    'mutation_type' => 'Sales',
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                ]);
            }


            DB::commit();


            return response()->json([
                'success' => true, 
                // 'cart_id' => $cart->id, 
                'totalcount' => $totalcount,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }
}