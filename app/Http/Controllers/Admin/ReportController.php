<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to = $request->query('to', now()->toDateString());

        $base = Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->where('status', '!=', 'dibatalkan');

        $summary = [
            'total_pesanan' => (clone $base)->count(),
            'total_pendapatan' => (clone $base)->where('payment_status', 'lunas')->sum('total'),
            'pesanan_selesai' => (clone $base)->where('status', 'selesai')->count(),
            'pesanan_dibatalkan' => Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                ->where('status', 'dibatalkan')->count(),
        ];

        $orders = (clone $base)->with('items')->latest()->paginate(10)->withQueryString();

        $topProducts = OrderItem::select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_omzet'))
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                    ->where('status', '!=', 'dibatalkan');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact('from', 'to', 'summary', 'orders', 'topProducts'));
    }
}
