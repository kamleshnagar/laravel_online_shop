<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\ShippingAddress;
use App\Models\ShippingAdress;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
        $product = Cart::get($request->rowId);

        if (!$product) {
            return response()->json([
                'status' =>  false,
                'error' =>  'Item not found in cart',
            ]);
        }



        $message = 'Item deleted successfully.';
        $cartCount = Cart::count();

        if ($cartCount > 0) {
            Cart::remove($request->rowId);

            $cartCount = Cart::count();
            $total = (float) Cart::subtotal(0, '', '');

            return response()->json([
                'status' =>  true,
                'message' =>  $message,
                'cartCount'  => $cartCount,
                'total'  => $total
            ]);
        }
    }


    public function updateCart(Request $request)
    {
        $product = Cart::get($request->rowId);

        if (!$product) {
            return response()->json([
                'status' =>  false,
                'error' =>  'Item not found in cart',
            ]);
        }

        Cart::update($request->rowId, $request->qty);

        $product_total = $product->qty * $product->price;
        $total = (float) Cart::subtotal(0, '', '');
        $message = 'Cart Updated Successfully!';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
            'total' => $total,
            'product_total' => $product_total,
        ]);
    }

    public function checkout()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $user = Auth::user();
        $shippingAddresses = ShippingAddress::where('user_id', $user->id)->get();
        return view('front.checkout', compact('countries', 'shippingAddresses'));
    }

    public function processCheckout(Request $request)
    {

        if ($request->payment_method != 'cod') {
            return redirect()
                ->back()
                ->withErrors(['payment_method' => 'Only Cash On Delivery is available.'])
                ->withInput();
        }


        $user_id = Auth::id();

        if ($request->saved_address == 'new') {

            $rules = [


                'first_name' => 'required|string|max:100',
                'last_name'  => 'required|string|max:100',

                'email' => 'required|email|max:255',

                'phone' => 'required|digits:10',

                'country_id' => 'required|integer|exists:countries,id',

                'address' => 'required|string|max:500',

                'apartment' => 'nullable|string|max:255',

                'city'  => 'required|string|max:100',
                'state' => 'required|string|max:100',

                'zip' => 'required|digits:6',
            ];

            $validated = $request->validate($rules);

            $validated['user_id'] = $user_id;

            $shippingAddress = ShippingAddress::create($validated);
        } else {
            $shippingAddress = ShippingAddress::findOrFail($request->saved_address);
        }

        $subtotal = Cart::subtotal(2, '.', '');
        $shipping = Shipping::where('country_id', $shippingAddress->country_id)->first()->shipping_charge;
        $coupon_code = '';
        $discount = 0;
        $grand_total = $subtotal + $shipping;

        $rules = [
            'payment_method' => 'required|in:cod,stripe',
            'order_notes' => 'nullable|string'
        ];

        $validated = $request->validate($rules);



        $order = Order::create([
            'user_id'  => $user_id,
            'subtotal'  => $subtotal,
            'shipping'  => $shipping,
            'coupon_code'  => $coupon_code,
            'discount'  => $discount,
            'grand_total'  => $grand_total,
            'payment_method'  => $validated['payment_method'],
            'first_name'  => $shippingAddress->first_name,
            'last_name'  => $shippingAddress->last_name,
            'email'  => $shippingAddress->email,
            'phone'  => $shippingAddress->phone,
            'country_id'  => $shippingAddress->country_id,
            'address'  => $shippingAddress->address,
            'apartment'  => $shippingAddress->apartment,
            'city'  => $shippingAddress->city,
            'state'  => $shippingAddress->state,
            'zip'  => $shippingAddress->zip,
            'order_notes'  => $validated['order_notes'],
        ]);


        foreach (Cart::content() as $item) {

            $order->items()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'total' => $item->qty * $item->price,
            ]);
        }

        Cart::destroy();

        return redirect()->route('front.thankyou',$order->id);
    }

    public function getShipping(Request $request)
    {

        $shipping = Shipping::where('country_id', $request->country_id)->first();

        if (!$shipping) {
            $shipping = Shipping::where('country_id', 'rest_of_world')->first();
        }

        return response()->json([
            'shipping' => $shipping->shipping_charge ?? 0
        ]);
    }

   

    public function thankyou($order_id)
    {
        return view('front.thankyou',compact('order_id'));
    }
}
