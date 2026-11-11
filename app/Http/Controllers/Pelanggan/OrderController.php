<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function status(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items');

        return view('pelanggan.orders.status', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('pelanggan.orders.history', compact('orders'));
    }
}
