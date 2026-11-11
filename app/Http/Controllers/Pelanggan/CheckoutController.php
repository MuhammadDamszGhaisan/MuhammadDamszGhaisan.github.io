<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderStatusUpdated;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Cart::all();

        if ($items->isEmpty()) {
            return redirect()->route('pelanggan.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $user = auth()->user();

        return view('pelanggan.checkout.index', compact('items', 'user'));
    }

    public function store(Request $request)
    {
        $items = Cart::all();

        if ($items->isEmpty()) {
            return redirect()->route('pelanggan.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:transfer,cod'],
        ]);

        $order = DB::transaction(function () use ($data, $items) {
            $order = Order::create([
                ...$data,
                'invoice_number' => Order::generateInvoiceNumber(),
                'user_id' => auth()->id(),
                'total' => $items->sum('subtotal'),
                'status' => 'menunggu',
                'payment_status' => 'belum_bayar',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['product']->price,
                    'qty' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['qty']);
            }

            return $order;
        });

        $order->user->notify(OrderStatusUpdated::forStatus($order));

        Cart::clear();

        return redirect()->route('pelanggan.payment.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }
}
