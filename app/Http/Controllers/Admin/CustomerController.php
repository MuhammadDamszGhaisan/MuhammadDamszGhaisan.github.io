<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'pelanggan')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_if(! $customer->isPelanggan(), 404);

        $orders = Order::where('user_id', $customer->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
