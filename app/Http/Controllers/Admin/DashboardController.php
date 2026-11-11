<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'produk' => Product::count(),
            'pelanggan' => User::where('role', 'pelanggan')->count(),
            'pesanan_baru' => Order::where('status', 'menunggu')->count(),
            'pendapatan' => Order::where('payment_status', 'lunas')->sum('total'),
        ];

        $latestOrders = Order::latest()->take(5)->get();
        $lowStock = Product::where('stock', '<=', 5)->orderBy('stock')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'lowStock'));
    }
}
