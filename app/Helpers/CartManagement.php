<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartManagement
{

    // add item to cart
    static public function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFromCart();

        $existing_item = null;

        foreach ($cart_items as $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $item['product_id'];
                $thisqty = $item['quantity'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }

        if ($existing_item !== null) {
            $data = Cart::where('product_id', $product_id)->where('created_by', auth()->user()->id);
            $thispoin = Product::where('id', $product_id)->value('poin');
            $thisweight = Product::where('id', $product_id)->value('weight');
            $update = [
                'quantity' => $thisqty + 1,
                'total_amount' => ($thisqty + 1) * $this_unit_amount,
                'total_weight' => ($thisqty + 1) * $thisweight,
                'poin' => ($thisqty + 1) * $thispoin,
            ];
            $data->update($update);
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'variant', 'slug', 'unit_name', 'weight', 'contain', 'price', 'poin', 'images', 'branch_id']);
            // $productimgempty = url('storage/box.png');
            if ($product->images != null) {
                $productimg = $product->images[0];
            } else {
                $productimg = '';
            }

            if ($product) {
                $cart_items = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'variant' => $product->variant,
                    'slug' => $product->slug,
                    'unit_name' => $product->unit_name,
                    'total_weight' => $product->weight,
                    'contain' => $product->contain,
                    'image' => $productimg,
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                    'poin' => $product->poin,
                    'mutation_type' => 'Sales',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'branch_id' => $product->branch_id
                ];
            }
            self::addCartItemsToCart($cart_items);
        }
        return count($cart_items);
    }

    // add item to cart with qty
    static public function addItemToCartWithQty($product_id, $qty)
    {
        $cart_items = self::getCartItemsFromCart();

        $existing_item = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $item['product_id'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }

        if ($existing_item !== null) {
            $data = Cart::where('product_id', $product_id)->where('created_by', auth()->user()->id);
            $thispoin = Product::where('id', $product_id)->value('poin');
            $thisweight = Product::where('id', $product_id)->value('weight');
            if ($qty < 1) {
                $qty = 1;
            } else {
                $qty = $qty;
            }
            $update = [
                'quantity' => $qty,
                'total_amount' => $qty * $this_unit_amount,
                'total_weight' => $qty * $thisweight,
                'poin' => $qty * $thispoin,
            ];
            $data->update($update);
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'variant', 'slug', 'unit_name', 'weight', 'contain', 'price', 'poin', 'images', 'branch_id']);
            // $productimgempty = url('storage/box.png');
            if ($product->images != null) {
                $productimg = $product->images[0];
            } else {
                $productimg = '';
            }
            if ($qty < 1) {
                $qty = 1;
            } else {
                $qty = $qty;
            }

            if ($product) {
                $cart_items = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'variant' => $product->variant,
                    'slug' => $product->slug,
                    'unit_name' => $product->unit_name,
                    'total_weight' => $product->weight * $qty,
                    'contain' => $product->contain,
                    'image' => $productimg,
                    'quantity' => $qty,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price * $qty,
                    'poin' => $product->poin * $qty,
                    'mutation_type' => 'Sales',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'branch_id' => $product->branch_id
                ];
            }
            self::addCartItemsToCart($cart_items);
        }
        return count($cart_items);
    }

    // add item to cart with qty on POS
    static public function addItemToCartWithQtyOnPos($product_id, $qty, $price)
    {
        $cart_items = self::getCartItemsFromCart();

        $existing_item = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $item['product_id'];
                $this_quantity = $item['quantity'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }

        if ($existing_item !== null) {
            $data = Cart::where('product_id', $product_id)->where('created_by', auth()->user()->id);
            $thispoin = Product::where('id', $product_id)->value('poin');
            $thisweight = Product::where('id', $product_id)->value('weight');
            if ($qty == null || $qty < 0) {
                $qty = $this_quantity;
            } else {
                $qty = $qty;
            }
            if ($price == null || $price < 0) {
                $price = $this_unit_amount;
            } else {
                $price = $price;
            }
            $update = [
                'quantity' => $qty,
                'unit_amount' => $price,
                'total_amount' => $qty * $price,
                'total_weight' => $qty * $thisweight,
                'poin' => $qty * $thispoin,
            ];
            $data->update($update);
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'variant', 'slug', 'unit_name', 'weight', 'contain', 'price', 'poin', 'images', 'branch_id']);
            // $productimgempty = url('storage/box.png');
            if ($product->images != null) {
                $productimg = $product->images[0];
            } else {
                $productimg = '';
            }
            if ($qty == null || $qty < 0) {
                $qty = 1;
            } else {
                $qty = $qty;
            }
            if ($price == null || $price < 0) {
                $price = $product->price;
            } else {
                $price = $price;
            }

            if ($product) {
                $cart_items = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'variant' => $product->variant,
                    'slug' => $product->slug,
                    'unit_name' => $product->unit_name,
                    'total_weight' => $product->weight * $qty,
                    'contain' => $product->contain,
                    'image' => $productimg,
                    'quantity' => $qty,
                    'unit_amount' => $price,
                    'total_amount' => $price * $qty,
                    'poin' => $product->poin * $qty,
                    'mutation_type' => 'Sales',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'branch_id' => $product->branch_id
                ];
            }
            self::addCartItemsToCart($cart_items);
        }
        return count($cart_items);
    }

    // remove item from cart
    static public function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCart();
        Cart::where('created_by', auth()->user()->id)->where('product_id', $product_id)->delete();
        return $cart_items;
    }

    // add cart items to cart
    static public function addCartItemsToCart($cart_items)
    {
        Cart::create($cart_items);
    }

    // clear cart items from cart
    static public function clearCartItems()
    {
        Cart::where('created_by', auth()->user()->id)->delete();
    }
    // clear cart items from cart filter branch
    static public function clearCartItemsOnBranch($branch_id)
    {
        Cart::where('created_by', auth()->user()->id)->where('branch_id', $branch_id)->delete();
    }

    // get all cart items from cart
    static public function getCartItemsFromCart()
    {
        if (Auth::check()) {
            $cart_items = Cart::all()->where('created_by', auth()->user()->id);
        } else {
            $cart_items = Cart::all()->where('created_by', 0);
        }
        if (!$cart_items) {
            $cart_items = [];
        }
        return $cart_items;
    }

    // increment item quantity
    static public function incrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCart();

        foreach ($cart_items as $item) {
            if ($item['product_id'] == $product_id) {
                $this_id = $item['product_id'];
                $thisqty = $item['quantity'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }
        $data = Cart::where('product_id', $this_id)->where('created_by', auth()->user()->id);
        $thisweight = Product::where('id', $product_id)->value('weight');
        $update = [
            'quantity' => $thisqty + 1,
            'total_amount' => ($thisqty + 1) * $this_unit_amount,
            'total_weight' => ($thisqty + 1) * $thisweight,
        ];
        $data->update($update);
        return $cart_items;
    }

    // decrement item quantity
    static public function decrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCart();

        foreach ($cart_items as $item) {
            if ($item['product_id'] == $product_id) {
                $this_id = $item['product_id'];
                $thisqty = $item['quantity'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }

        if ($thisqty > 1) {
            $data = Cart::where('product_id', $this_id)->where('created_by', auth()->user()->id);
            $thisweight = Product::where('id', $product_id)->value('weight');
            $update = [
                'quantity' => $thisqty - 1,
                'total_amount' => ($thisqty - 1) * $this_unit_amount,
                'total_weight' => ($thisqty - 1) * $thisweight,
            ];
            $data->update($update);
        }
        return $cart_items;
    }

    // typing item quantity
    static public function typingQuantityToCartItem($id, $thisqty)
    {
        $cart_items = self::getCartItemsFromCart();

        foreach ($cart_items as $item) {
            if ($item['id'] == $id) {
                $this_id = $item['id'];
                $this_unit_amount = $item['unit_amount'];
                break;
            }
        }
        $data = Cart::where('id', $this_id)->where('created_by', auth()->user()->id);
        $update = [
            'quantity' => $thisqty,
            'total_amount' => $thisqty * $this_unit_amount,
        ];
        $data->update($update);
        return $cart_items;
    }

    // calculate grand total
    static public function calculateGrandTotal($items)
    {
        if (Auth::check()) {
            $items = Cart::query()->where('created_by', auth()->user()->id)->sum('total_amount');
        } else {
            $items = Cart::query()->where('created_by', 0)->sum('total_amount');
        }
        return ($items);
    }
    // calculate grand total by branch
    static public function calculateGrandTotalByBranch($items)
    {
        if (Auth::check()) {
            $items = Cart::query()->where('created_by', auth()->user()->id)->where('branch_id', auth()->user()->branch_id)->sum('total_amount');
        } else {
            $items = Cart::query()->where('created_by', 0)->where('branch_id', auth()->user()->branch_id)->sum('total_amount');
        }
        return ($items);
    }
}
