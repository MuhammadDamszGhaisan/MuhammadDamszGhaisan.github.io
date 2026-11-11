@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-box-seam"></i> Katalog Produk</h4>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari produk...">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
    </div>
</form>

<div class="row g-3">
    @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 product-card">
                <div class="product-media">
                    <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted">{{ $product->category->name ?? 'Tanpa kategori' }}</small>
                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                    <div class="fw-bold text-primary mb-2">{{ $product->rupiah_price }}</div>
                    <small class="text-muted mb-2">Stok: {{ $product->stock }} {{ $product->unit }}</small>
                    <a href="{{ route('pelanggan.products.show', $product) }}" class="btn btn-sm btn-outline-primary mt-auto product-cta">Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <div class="fw-semibold">Produk tidak ditemukan</div>
                    <small>Coba kata kunci atau kategori lain.</small>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
