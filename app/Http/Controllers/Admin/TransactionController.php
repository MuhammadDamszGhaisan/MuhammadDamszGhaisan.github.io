<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($payment = $request->query('payment')) {
            $query->where('payment_status', $payment);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.transactions.index', compact('orders'));
    }

    public function receipt(Order $order)
    {
        $order->load('items');

        return view('admin.transactions.receipt', compact('order'));
    }

    public function whatsapp(Order $order)
    {
        $order->load('items');

        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $lines = [];
        $lines[] = '*STRUK PEMBELIAN*';
        $lines[] = 'MAGSTORE';
        $lines[] = '========================';
        $lines[] = 'No. Invoice : '.$order->invoice_number;
        $lines[] = 'Tanggal     : '.$order->created_at->format('d/m/Y H:i');
        $lines[] = 'Pelanggan   : '.$order->customer_name;
        $lines[] = '========================';

        foreach ($order->items as $item) {
            $lines[] = $item->product_name.' x'.$item->qty;
            $lines[] = '  Rp '.number_format((float) $item->subtotal, 0, ',', '.');
        }

        $lines[] = '========================';
        $lines[] = 'TOTAL : Rp '.number_format((float) $order->total, 0, ',', '.');
        $lines[] = 'Status Pembayaran : '.$order->payment_label;
        $lines[] = 'Status Pesanan    : '.$order->status_label;
        $lines[] = 'Terima kasih telah berbelanja.';

        $text = urlencode(implode("\n", $lines));

        return redirect()->away("https://wa.me/{$phone}?text={$text}");
    }
}
