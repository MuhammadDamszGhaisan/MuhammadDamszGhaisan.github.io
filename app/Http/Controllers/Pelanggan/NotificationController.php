<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('pelanggan.orders.history'));
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
