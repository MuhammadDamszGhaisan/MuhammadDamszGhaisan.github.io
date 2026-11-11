@extends('layouts.admin')

@section('title', 'Kelola Stok & Harga')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-boxes"></i> Kelola Stok & Harga</h4>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari produk..."></div>
    <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button></div>
</form>

<form method="POST" action="{{ route('admin.stocks.bulk') }}">
    @csrf
    @method('PATCH')
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Produk</th><th>Kategori</th><th style="width:200px">Harga (Rp)</th><th style="width:150px">Stok</th></tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <input type="hidden" name="products[{{ $i }}][id]" value="{{ $product->id }}">
                                <input type="number" name="products[{{ $i }}][price]" value="{{ (int) $product->price }}" class="form-control form-control-sm" min="0" required>
                            </td>
                            <td>
                                <input type="number" name="products[{{ $i }}][stock]" value="{{ $product->stock }}" class="form-control form-control-sm @if($product->stock <= 5) border-danger @endif" min="0" required>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->count())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">Ubah harga/stok lalu klik simpan.</small>
                <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
            </div>
        @endif
    </div>
</form>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
