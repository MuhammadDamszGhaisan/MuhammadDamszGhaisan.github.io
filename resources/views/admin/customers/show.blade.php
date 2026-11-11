@extends('layouts.admin')

@section('title', 'Detail Pelanggan')

@section('content')
<a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-semibold">Profil Pelanggan</div>
            <div class="card-body">
                <p class="mb-1"><strong>Nama:</strong> {{ $customer->name }}</p>
                <p class="mb-1"><strong>Username:</strong> {{ $customer->username }}</p>
                <p class="mb-1"><strong>No. WA:</strong> {{ $customer->phone }}</p>
                <p class="mb-1"><strong>Alamat:</strong> {{ $customer->address }}</p>
                <p class="mb-0"><strong>Bergabung:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-semibold">Riwayat Pemesanan</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Invoice</th><th>Tanggal</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->invoice_number }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td>{{ $order->rupiah_total }}</td>
                                <td><span class="badge bg-info text-dark">{{ $order->status_label }}</span></td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Lihat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
