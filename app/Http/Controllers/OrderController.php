<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $orders = Order::where('user_id', $user_id)->paginate(10);
        return view('front.orders', compact('orders'));
    }

    public function orderSummery($id)
    {
        $order = Order::findOrFail($id);
        return view('front.orders_details', compact('order'));
    }
}
