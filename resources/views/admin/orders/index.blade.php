@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-receipt"></i> Kelola Pesanan</h4>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari invoice / nama pelanggan...">
    </div>
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Invoice</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Bayar</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->invoice_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->rupiah_total }}</td>
                        <td><span class="badge bg-info text-dark">{{ $order->status_label }}</span></td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'lunas' ? 'success' : ($order->payment_status === 'menunggu_verifikasi' ? 'warning text-dark' : 'secondary') }}">
                                {{ $order->payment_label }}
                            </span>
                        </td>
                        <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Kelola</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
