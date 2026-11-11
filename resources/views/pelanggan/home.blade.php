@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="hero-banner p-4 p-md-5 mb-4 rounded-4 text-white position-relative overflow-hidden">
    <div class="hero-float" aria-hidden="true">
        <span class="hero-float-icon"><i class="bi bi-cart3"></i></span>
        <span class="hero-float-icon"><i class="bi bi-box-seam"></i></span>
        <span class="hero-float-icon"><i class="bi bi-snow2"></i></span>
    </div>
    <div class="row align-items-center g-4 position-relative">
        <div class="col-lg-8">
            <span class="badge bg-light text-dark fw-semibold mb-3 hero-reveal" style="--hero-delay: 100ms">
                <i class="bi bi-stars"></i> Smart Shopping, Easy Living.
            </span>
            <h2 class="fw-bold mb-2 hero-reveal" style="--hero-delay: 200ms">Halo, {{ auth()->user()->name }}! &#128075;</h2>
            <p class="hero-text mb-4 hero-reveal" style="--hero-delay: 300ms">Temukan perabotan rumah tangga dan frozen food berkualitas dengan harga terbaik.</p>
            <div class="d-flex flex-wrap gap-2 hero-reveal" style="--hero-delay: 400ms">
                <a href="{{ route('pelanggan.products.index') }}" class="btn btn-light fw-semibold px-3">
                    <i class="bi bi-grid"></i> Lihat Produk
                </a>
                <a href="{{ route('pelanggan.orders.history') }}" class="btn btn-outline-light px-3">
                    <i class="bi bi-clock-history"></i> Riwayat Pesanan
                </a>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <span class="hero-logo-wrap hero-reveal" style="--hero-delay: 0ms">
                <img src="{{ asset('img/logo.png') }}" alt="MAGSTORE" class="hero-logo">
            </span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-orange"><i class="bi bi-truck"></i></span>
                <div>
                    <div class="fw-bold">Pengiriman Cepat</div>
                    <small class="text-muted">Pesanan diproses hari yang sama.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-sky"><i class="bi bi-shield-check"></i></span>
                <div>
                    <div class="fw-bold">Pembayaran Aman</div>
                    <small class="text-muted">Transfer bank atau bayar di tempat.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="stat-chip chip-green"><i class="bi bi-patch-check"></i></span>
                <div>
                    <div class="fw-bold">Produk Berkualitas</div>
                    <small class="text-muted">Perabotan &amp; frozen food pilihan.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-tags text-primary fs-5"></i>
    <h5 class="section-heading mb-0">Kategori</h5>
</div>
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('pelanggan.products.index') }}" class="btn btn-outline-primary btn-sm category-chip">
        <i class="bi bi-collection"></i> Semua
    </a>
    @foreach($categories as $category)
        <a href="{{ route('pelanggan.products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary btn-sm category-chip">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<div class="produk-section">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-box-seam text-primary fs-5"></i>
            <h5 class="section-heading mb-0">Produk Kami</h5>
            <span class="badge bg-primary-subtle text-primary-emphasis">{{ $products->count() }} produk</span>
        </div>
        @if($products->isNotEmpty())
            <ul class="nav nav-pills gap-2" id="produkTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-kategori-btn" data-bs-toggle="pill" data-bs-target="#tab-kategori" type="button" role="tab" aria-controls="tab-kategori" aria-selected="true">
                        <i class="bi bi-grid-3x3-gap"></i> Per Kategori
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-semua-btn" data-bs-toggle="pill" data-bs-target="#tab-semua" type="button" role="tab" aria-controls="tab-semua" aria-selected="false">
                        <i class="bi bi-collection"></i> Semua Produk
                    </button>
                </li>
            </ul>
        @endif
    </div>

    @if($products->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <div class="fw-semibold">Belum ada produk</div>
            <small>Produk akan segera tersedia.</small>
        </div>
    @else
        @php
            $kategoriTerisi = $categories->filter(fn ($category) => $products->where('category_id', $category->id)->isNotEmpty());
        @endphp

        <div class="tab-content" id="produkTabContent">
            {{-- Tampilan 1: produk disusun/dikelompokkan per kategori --}}
            <div class="tab-pane fade show active" id="tab-kategori" role="tabpanel" aria-labelledby="tab-kategori-btn" tabindex="0">
                @forelse($kategoriTerisi as $category)
                    @php
                        $items = $products->where('category_id', $category->id);
                        $nama = str($category->name)->lower();
                        $icon = match (true) {
                            $nama->contains(['frozen', 'beku', 'es']) => 'bi-snow2',
                            $nama->contains(['dapur', 'masak']) => 'bi-cup-hot',
                            $nama->contains(['perabot', 'rumah', 'bersih', 'kebersihan']) => 'bi-house-heart',
                            default => 'bi-tags',
                        };
                    @endphp
                    <div class="category-block">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="category-icon"><i class="bi {{ $icon }}"></i></span>
                                <div>
                                    <h6 class="section-heading mb-0">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $items->count() }} produk</small>
                                </div>
                            </div>
                            <a href="{{ route('pelanggan.products.index', ['category' => $category->id]) }}" class="btn btn-sm btn-link fw-semibold text-decoration-none">
                                Lihat semua <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="row g-3">
                            @foreach($items as $product)
                                @include('pelanggan.partials.product-card', ['product' => $product, 'showCategory' => false])
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <div class="fw-semibold">Belum ada produk per kategori</div>
                        <small>Silakan lihat tab Semua Produk.</small>
                    </div>
                @endforelse
            </div>

            {{-- Tampilan 2: semua produk dalam satu kesatuan --}}
            <div class="tab-pane fade" id="tab-semua" role="tabpanel" aria-labelledby="tab-semua-btn" tabindex="0">
                <div class="row g-3">
                    @foreach($products as $product)
                        @include('pelanggan.partials.product-card', ['product' => $product, 'showCategory' => true])
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
