<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $product = Product::with('product_images')
            ->find($request->id);
        if ($product == null) {
            return response()->json([
                'status'     => true,
                'message' => 'Something went wrong.'
            ]);
        }


        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productExists = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productExists = true;
                };
            }
            if ($productExists == true) {
                $message = ucfirst($product->title) . ' already in cart';
                $status = true;
            } else {
                $productImage = (!empty($product->product_images) ? $product->product_images->first() : '');
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $productImage]);
                $status = true;
                $message = ucfirst($product->title) . ' added in cart';
            }
        } else {
            $productImage = (!empty($product->product_images) ? $product->product_images->first() : '');
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $productImage]);
            $status = true;
            $message = ucfirst($product->title) . ' added in cart';
        }

        return response()->json([
            'status'  => $status ?? false,
            'message'  => $message ?? '',
            'cartCount'  => Cart::count() ?? 0
        ]);
    }

    public function cart()
    {
        $cartContent = Cart::content() ?? '';
        return view('front.cart', compact('cartContent'));
    }

    public function deletCartItem(Request $request)
    {
        $cartCount = Cart::count();
        if ($cartCount > 0) {
            Cart::remove($request->rowId);
            return response()->json([
                'status' =>  true,
                'message' =>  'Item deleted successfully',
                'cartCount'  => Cart::count() ?? 0
            ]);
        }
    }


    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        Cart::update($request->rowId, $request->qty);
        // $cart =  Cart::content();
        $message = 'Cart Updated Successfully!';

        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
