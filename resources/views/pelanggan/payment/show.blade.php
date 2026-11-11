@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-credit-card"></i> Pembayaran Pesanan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">No. Invoice</span>
                    <span class="fw-semibold">{{ $order->invoice_number }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Metode</span>
                    <span class="text-uppercase">{{ $order->payment_method }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Tagihan</span>
                    <span class="fw-bold text-primary fs-5">{{ $order->rupiah_total }}</span>
                </div>

                @if($order->payment_method === 'transfer')
                    <div class="alert alert-info">
                        Silakan transfer ke rekening berikut:<br>
                        <strong>Bank BCA - 1234567890</strong><br>
                        a.n. MAGSTORE
                    </div>
                @else
                    <div class="alert alert-info">Pembayaran dilakukan saat barang diterima (COD).</div>
                @endif

                <hr>
                <h6>Unggah Bukti Pembayaran</h6>
                @if($order->payment_proof)
                    <p class="text-success small upload-success-note"><i class="bi bi-check-circle"></i> Bukti pembayaran sudah diunggah (status: {{ $order->payment_label }}).</p>
                @endif
                <form method="POST" action="{{ route('pelanggan.payment.upload', $order) }}" enctype="multipart/form-data" data-upload-form>
                    @csrf
                    <div class="upload-zone" data-upload-zone>
                        <div class="upload-zone-head">
                            <span class="upload-zone-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                            <div>
                                <strong>Pilih foto bukti transfer</strong>
                                <small>JPG, PNG, atau WEBP · maksimal 10 MB</small>
                            </div>
                        </div>
                        <input type="file" name="payment_proof" id="payment-proof" accept="image/jpeg,image/png,image/webp" class="form-control @error('payment_proof') is-invalid @enderror" data-preview="payment-proof-preview" required>
                        @error('payment_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="upload-zone-status" data-upload-status aria-live="polite">Belum ada file dipilih.</div>
                        <img id="payment-proof-preview" class="upload-preview d-none mt-2" alt="Pratinjau foto bukti">
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-upload"></i> Unggah Bukti</button>
                        <a href="{{ route('pelanggan.orders.status', $order) }}" class="btn btn-outline-secondary">Lihat Status Pesanan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
