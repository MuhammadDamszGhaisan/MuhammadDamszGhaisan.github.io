@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-title mb-4">
    <span class="pi"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard<small>Ringkasan operasional toko</small></span>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-orange"><i class="bi bi-box-seam"></i></span>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value" data-count-to="{{ $stats['produk'] }}">{{ $stats['produk'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-sky"><i class="bi bi-people"></i></span>
                <div>
                    <div class="stat-label">Pelanggan</div>
                    <div class="stat-value" data-count-to="{{ $stats['pelanggan'] }}">{{ $stats['pelanggan'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-amber"><i class="bi bi-receipt"></i></span>
                <div>
                    <div class="stat-label">Pesanan Baru</div>
                    <div class="stat-value" data-count-to="{{ $stats['pesanan_baru'] }}">{{ $stats['pesanan_baru'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-green"><i class="bi bi-cash-coin"></i></span>
                <div>
                    <div class="stat-label">Pendapatan (Lunas)</div>
                    <div class="stat-value" data-count-to="{{ $stats['pendapatan'] }}" data-count-prefix="Rp ">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-semibold">Pesanan Terbaru</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Invoice</th><th>Pelanggan</th><th>Total</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                            <tr>
                                <td>{{ $order->invoice_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->rupiah_total }}</td>
                                <td><span class="badge bg-info text-dark">{{ $order->status_label }}</span></td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-semibold">Stok Menipis (<= 5)</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Produk</th><th>Stok</th></tr></thead>
                    <tbody>
                        @forelse($lowStock as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Semua stok aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
