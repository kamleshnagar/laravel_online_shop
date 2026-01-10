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
        $user = Auth::user();
        $subtotal = Cart::subtotal(2, '.', '');

        $products = Cart::content();

        $user_id = $user->id;
        $subtotal = $subtotal;
        $shipping = 0;
        $discount = 0;
        $grand_total = $subtotal + $validated['shipping'] = 0;
        $payment_status = 'pending';

        if ($request->saved_address != 'new') {

            $validated = $request->validate([
                'saved_address' => 'required|integer|exists:shipping_addresses,id',
                // 'coupon_code'  => 'nullable|string|max:50',
                'payment_method' => 'required|in:cod,stripe',
                'order_notes' => 'nullable|string|max:1000',
            ]);

            $shippingAdress = ShippingAddress::where('id', $validated['saved_address'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $order = new Order();
            $order->user_id = $user_id;
            $order->subtotal = $subtotal;
            $order->shipping = $shipping;
            $order->discount = $discount;
            $order->grand_total = $grand_total;
            // $order->coupon_code =  $validated['coupon_code'];
            $order->payment_method =  $validated['payment_method'];
            $order->payment_status = $payment_status;
            $order->first_name = $shippingAdress->first_name;
            $order->last_name = $shippingAdress->last_name;
            $order->email = $shippingAdress->email;
            $order->phone = $shippingAdress->phone;
            $order->country_id  = $shippingAdress->country_id;
            $order->address = $shippingAdress->address;
            $order->apartment = $shippingAdress->apartment;
            $order->city = $shippingAdress->city;
            $order->state = $shippingAdress->state;
            $order->zip = $shippingAdress->zip; 
            $order->order_notes = $validated['order_notes'];
            $order->save();
        } else {

          
            $validated = $request->validate([

                'coupon_code'  => 'nullable|string|max:50',
                /* ========= Payment ========= */
                'payment_method' => 'required|in:cod,stripe',

                /* ========= Address ========= */
                'first_name' => 'required|string|max:100',
                'last_name'  => 'required|string|max:100',
                'email'      => 'required|email|max:150',
                'phone'      => 'required|string|digits:10',

                'country_id' => 'required|exists:countries,id',

                'address'    => 'required|string|max:500',
                'apartment'  => 'nullable|string|max:255',
                'city'       => 'required|string|max:100',
                'state'      => 'required|string|max:100',
                'zip'        => 'required|string|max:20',

                'order_notes' => 'nullable|string|max:1000',

            ]);
            
            if ($validated['payment_method'] === 'stripe') {
                return redirect()->back()
                ->withErrors(['payment_method' => 'Online payment method is not supported yet.'])
                ->withInput();
            }
            
            if ($validated['payment_method'] == 'cod') {
                $subtotal = $subtotal;
                $shipping = 0;
                $discount = 0;
                $grand_total = $subtotal + $validated['shipping'] = 0;
                $payment_status = 'pending';
                
                $validated['user_id'] = $user->id;
                $validated['subtotal'] = $subtotal;
                $validated['shipping'] = $shipping;
                $validated['discount'] = $discount;
                $validated['grand_total'] = $grand_total;
                // dd($validated['country_id']);
                $order = Order::create($validated);
                $shippingAdress = ShippingAddress::create(
                    [
                        'user_id' => $user_id,
                        'first_name' => $validated['first_name'],
                        'last_name' => $validated['last_name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'country_id'  => $validated['country_id'],
                        'address' => $validated['address'],
                        'apartment' => $validated['apartment'],
                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'zip' => $validated['zip'],
                    ]
                );
            }
        }
    }
}
