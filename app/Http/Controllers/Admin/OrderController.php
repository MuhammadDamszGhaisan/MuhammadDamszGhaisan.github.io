<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::withCount('items')->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        $statuses = Order::STATUS_LABELS;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,dikirim,selesai,dibatalkan'],
        ]);

        $oldStatus = $order->status;
        $order->status = $data['status'];

        if ($data['status'] === 'selesai') {
            $order->delivered_at = $order->delivered_at ?? now();
        }

        $order->save();

        if ($oldStatus !== $order->status && $order->user) {
            $order->user->notify(OrderStatusUpdated::forStatus($order));
        }

        return back()->with('success', 'Status pesanan diperbarui menjadi '.$order->fresh()->status_label.'.');
    }

    public function verifyPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'lunas',
            'paid_at' => now(),
        ]);

        if ($order->user) {
            $order->user->notify(OrderStatusUpdated::paymentVerified($order));
        }

        return back()->with('success', 'Pembayaran ditandai lunas.');
    }
}
