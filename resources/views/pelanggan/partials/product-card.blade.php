{{-- Kartu produk (dipakai di beranda) --}}
<div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100 product-card">
        <div class="position-relative product-media">
            <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
            @if(($showCategory ?? true) && $product->category)
                <span class="badge bg-dark position-absolute top-0 end-0 m-2">
                    {{ $product->category->name }}
                </span>
            @endif
            @if($product->stock <= 0)
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                    <i class="bi bi-x-circle"></i> Habis
                </span>
            @endif
        </div>
        <div class="card-body d-flex flex-column">
            <h6 class="card-title mb-1">{{ $product->name }}</h6>
            <div class="fw-bold text-primary mb-2">{{ $product->rupiah_price }}</div>
            <small class="text-muted mb-3">
                <i class="bi bi-box"></i> Stok: {{ $product->stock }} {{ $product->unit }}
            </small>
            <a href="{{ route('pelanggan.products.show', $product) }}" class="btn btn-sm btn-outline-primary mt-auto product-cta">
                <i class="bi bi-eye"></i> Detail
            </a>
        </div>
    </div>
</div>
