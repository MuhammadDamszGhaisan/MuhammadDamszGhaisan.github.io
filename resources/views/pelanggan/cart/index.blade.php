@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<h4 class="mb-3"><i class="bi bi-cart"></i> Keranjang Belanja</h4>

@if($items->isEmpty())
    <div class="card">
        <div class="empty-state">
            <i class="bi bi-cart-x"></i>
            <div class="fw-semibold">Keranjang masih kosong</div>
            <small class="d-block mb-3">Yuk mulai belanja kebutuhan Anda.</small>
            <a href="{{ route('pelanggan.products.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-grid"></i> Lihat Produk</a>
        </div>
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th style="width:160px">Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $item['product']->image_url }}" width="50" height="50" class="rounded object-fit-cover" alt="">
                                    <div>
                                        <div class="fw-semibold">{{ $item['product']->name }}</div>
                                        <small class="text-muted">Stok: {{ $item['product']->stock }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item['product']->rupiah_price }}</td>
                            <td>
                                <form method="POST" action="{{ route('pelanggan.cart.update', $item['product']) }}" class="d-flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="{{ $item['product']->stock }}" class="form-control form-control-sm" style="width:80px">
                                    <button class="btn btn-sm btn-outline-secondary" title="Perbarui"><i class="bi bi-arrow-repeat"></i></button>
                                </form>
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('pelanggan.cart.remove', $item['product']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <th colspan="3" class="text-end">Total</th>
                        <th colspan="2">Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <form method="POST" action="{{ route('pelanggan.cart.clear') }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger"><i class="bi bi-x-circle"></i> Kosongkan</button>
        </form>
        <a href="{{ route('pelanggan.checkout.index') }}" class="btn btn-primary">
            Lanjut Checkout <i class="bi bi-arrow-right"></i>
        </a>
    </div>
@endif
@endsection
