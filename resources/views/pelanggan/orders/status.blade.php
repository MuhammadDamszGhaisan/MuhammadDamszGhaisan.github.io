@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('content')
<a href="{{ route('pelanggan.orders.history') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Riwayat Pesanan</a>

@php
    $steps = ['menunggu', 'diproses', 'dikirim', 'selesai'];
    $currentIndex = array_search($order->status, $steps, true);
@endphp

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">{{ $order->invoice_number }}</span>
        <span class="badge bg-{{ $order->status === 'dibatalkan' ? 'danger' : ($order->status === 'selesai' ? 'success' : 'warning text-dark') }}">{{ $order->status_label }}</span>
    </div>
    <div class="card-body">
        @if($order->status === 'dibatalkan')
            <div class="alert alert-danger mb-3">Pesanan ini telah dibatalkan.</div>
        @else
            <div class="d-flex justify-content-between mb-4">
                @foreach($steps as $i => $step)
                    <div class="text-center flex-fill">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                            {{ $currentIndex !== false && $i <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted' }}"
                            style="width:40px;height:40px;">
                            <i class="bi bi-{{ $i <= $currentIndex && $currentIndex !== false ? 'check-lg' : 'circle' }}"></i>
                        </div>
                        <small class="d-block mt-1 {{ $i <= $currentIndex && $currentIndex !== false ? 'fw-semibold' : 'text-muted' }}">{{ \App\Models\Order::STATUS_LABELS[$step] }}</small>
                    </div>
                @endforeach
            </div>
        @endif

        @if($order->status !== 'dibatalkan')
            <div class="alert alert-info d-flex align-items-start gap-2 mb-3">
                <i class="bi bi-info-circle-fill mt-1"></i>
                <div>
                    <strong>Status selanjutnya:</strong> {{ $order->next_step }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Penerima:</strong> {{ $order->customer_name }}</p>
                <p class="mb-1"><strong>No. WA:</strong> {{ $order->customer_phone }}</p>
                <p class="mb-1"><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                @if($order->note) <p class="mb-1"><strong>Catatan:</strong> {{ $order->note }}</p> @endif
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Metode Bayar:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p class="mb-1"><strong>Status Bayar:</strong> {{ $order->payment_label }}</p>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                @if($order->delivered_at)
                    <p class="mb-1"><strong>Diterima:</strong> {{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <hr>
        <table class="table table-sm">
            <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot><tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td>{{ $order->rupiah_total }}</td></tr></tfoot>
        </table>

        @if($order->payment_status !== 'lunas')
            <a href="{{ route('pelanggan.payment.show', $order) }}" class="btn btn-primary btn-sm"><i class="bi bi-credit-card"></i> Lakukan Pembayaran</a>
        @endif
    </div>
</div>
@endsection
