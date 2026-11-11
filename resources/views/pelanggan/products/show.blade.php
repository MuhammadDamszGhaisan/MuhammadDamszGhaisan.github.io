@extends('layouts.app')

@section('title', $product->name)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('pelanggan.products.index') }}">Produk</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        <img src="{{ $product->image_url }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $product->name }}" data-cart-image>
    </div>
    <div class="col-md-7">
        <span class="badge bg-secondary">{{ $product->category->name ?? 'Tanpa kategori' }}</span>
        <h3 class="mt-2">{{ $product->name }}</h3>
        <h4 class="text-primary">{{ $product->rupiah_price }} <small class="text-muted fs-6">/ {{ $product->unit }}</small></h4>
        <p class="text-muted">Stok tersedia: {{ $product->stock }} {{ $product->unit }}</p>
        <p>{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>

        @if($product->stock > 0)
            <form method="POST" action="{{ route('pelanggan.cart.add', $product) }}" class="row g-2 align-items-end" data-cart-form>
                @csrf
                <div class="col-auto">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:100px">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-cart-plus"></i> Tambah ke Keranjang</button>
                </div>
            </form>
        @else
            <div class="alert alert-warning">Stok habis.</div>
        @endif
    </div>
</div>

@if($related->isNotEmpty())
    <h5 class="mt-5 mb-3">Produk Terkait</h5>
    <div class="row g-3">
        @foreach($related as $item)
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card">
                    <div class="product-media">
                        <img src="{{ $item->image_url }}" class="card-img-top product-img" alt="{{ $item->name }}">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $item->name }}</h6>
                        <div class="fw-bold text-primary">{{ $item->rupiah_price }}</div>
                        <a href="{{ route('pelanggan.products.show', $item) }}" class="btn btn-sm btn-outline-primary mt-2 w-100 product-cta">Lihat</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
