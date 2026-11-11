@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<h4 class="mb-3"><i class="bi bi-clock-history"></i> Riwayat Pesanan</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->invoice_number }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->items_count }} produk</td>
                        <td>{{ $order->rupiah_total }}</td>
                        <td><span class="badge bg-info text-dark">{{ $order->status_label }}</span></td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'lunas' ? 'success' : ($order->payment_status === 'menunggu_verifikasi' ? 'warning text-dark' : 'secondary') }}">
                                {{ $order->payment_label }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('pelanggan.orders.status', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
