@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h4 class="mb-3"><i class="bi bi-bag-check"></i> Checkout</h4>

<div class="checkout-steps" style="--step-progress: 40%" aria-label="Langkah checkout">
    <div class="checkout-step is-done">
        <span class="step-dot"><i class="bi bi-check-lg"></i></span>
        <span>Keranjang</span>
    </div>
    <div class="checkout-step is-active" aria-current="step">
        <span class="step-dot">2</span>
        <span>Checkout</span>
    </div>
    <div class="checkout-step">
        <span class="step-dot">3</span>
        <span>Pembayaran</span>
    </div>
</div>

<form method="POST" action="{{ route('pelanggan.checkout.store') }}">
    @csrf
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header fw-semibold">Data Pengiriman</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" class="form-control @error('customer_name') is-invalid @enderror" required>
                        @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. WhatsApp</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone) }}" class="form-control @error('customer_phone') is-invalid @enderror" required>
                        @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Pengiriman</label>
                        <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" rows="3" required>{{ old('customer_address', $user->address) }}</textarea>
                        @error('customer_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer Bank</option>
                            <option value="cod" @selected(old('payment_method') === 'cod')>COD (Bayar di Tempat)</option>
                        </select>
                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header fw-semibold">Ringkasan Pesanan</div>
                <div class="card-body">
                    @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item['product']->name }} <small class="text-muted">x{{ $item['qty'] }}</small></span>
                            <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3"><i class="bi bi-check-circle"></i> Buat Pesanan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
