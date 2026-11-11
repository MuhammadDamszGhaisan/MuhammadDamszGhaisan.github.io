@extends('layouts.admin')

@section('title', 'Transaksi & Struk')

@section('content')
<h4 class="mb-3"><i class="bi bi-cash-coin"></i> Transaksi & Struk</h4>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari invoice / pelanggan..."></div>
    <div class="col-md-4">
        <select name="payment" class="form-select">
            <option value="">Semua Pembayaran</option>
            @foreach(\App\Models\Order::PAYMENT_LABELS as $key => $label)
                <option value="{{ $key }}" @selected(request('payment') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button></div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Invoice</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Bayar</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->invoice_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->rupiah_total }}</td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status === 'lunas' ? 'success' : ($order->payment_status === 'menunggu_verifikasi' ? 'warning text-dark' : 'secondary') }}">
                                {{ $order->payment_label }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.transactions.receipt', $order) }}" target="_blank" class="btn btn-sm btn-outline-dark"><i class="bi bi-printer"></i> Struk</a>
                            <a href="{{ route('admin.transactions.whatsapp', $order) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> Kirim</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
