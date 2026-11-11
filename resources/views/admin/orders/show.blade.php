@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">{{ $order->invoice_number }}</span>
                <span class="badge bg-info text-dark">{{ $order->status_label }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>No. WA:</strong> {{ $order->customer_phone }}</p>
                        <p class="mb-1"><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                        @if($order->note) <p class="mb-1"><strong>Catatan:</strong> {{ $order->note }}</p> @endif
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Akun:</strong> {{ $order->user->username ?? '-' }}</p>
                        <p class="mb-1"><strong>Metode Bayar:</strong> {{ strtoupper($order->payment_method) }}</p>
                        <p class="mb-1"><strong>Status Bayar:</strong> {{ $order->payment_label }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        @if($order->delivered_at)
                            <p class="mb-1"><strong>Diterima:</strong> {{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

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

                @if($order->payment_proof)
                    <hr>
                    <h6>Bukti Pembayaran</h6>
                    <a href="{{ asset($order->payment_proof) }}" target="_blank">
                        <img src="{{ asset($order->payment_proof) }}" class="img-thumbnail" style="max-height:250px" alt="Bukti">
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Ubah Status Pesanan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Status</button>
                </form>
            </div>
        </div>

        @if($order->payment_status !== 'lunas')
            <div class="card mb-3">
                <div class="card-header fw-semibold">Verifikasi Pembayaran</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.verify', $order) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success w-100"><i class="bi bi-check2-circle"></i> Tandai Lunas</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header fw-semibold">Transaksi & Struk</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.transactions.receipt', $order) }}" target="_blank" class="btn btn-outline-dark"><i class="bi bi-printer"></i> Cetak Struk</a>
                <a href="{{ route('admin.transactions.whatsapp', $order) }}" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim via WhatsApp</a>
            </div>
        </div>
    </div>
</div>
@endsection
