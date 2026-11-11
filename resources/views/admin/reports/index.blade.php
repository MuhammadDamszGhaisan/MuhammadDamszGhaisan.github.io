@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Laporan Penjualan</h4>
    <button data-print class="btn btn-outline-dark"><i class="bi bi-printer"></i> Cetak Laporan</button>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <label class="form-label small">Dari Tanggal</label>
        <input type="date" name="from" value="{{ $from }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label small">Sampai Tanggal</label>
        <input type="date" name="to" value="{{ $to }}" class="form-control">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Tampilkan</button>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Total Pesanan</div>
            <div class="fs-3 fw-bold">{{ $summary['total_pesanan'] }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Pendapatan (Lunas)</div>
            <div class="fs-5 fw-bold">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Pesanan Selesai</div>
            <div class="fs-3 fw-bold text-success">{{ $summary['pesanan_selesai'] }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Pesanan Dibatalkan</div>
            <div class="fs-3 fw-bold text-danger">{{ $summary['pesanan_dibatalkan'] }}</div>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-semibold">Produk Terlaris</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Produk</th><th>Terjual</th><th>Omzet</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->total_qty }}</td>
                                <td>Rp {{ number_format($product->total_omzet, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-semibold">Daftar Transaksi ({{ $from }} s/d {{ $to }})</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->invoice_number }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->rupiah_total }}</td>
                                <td><span class="badge bg-info text-dark">{{ $order->status_label }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
