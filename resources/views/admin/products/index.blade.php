@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-box-seam"></i> Kelola Produk</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari produk..."></div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button></div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><img src="{{ $product->image_url }}" width="45" height="45" class="rounded object-fit-cover" alt=""></td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->rupiah_price }}</td>
                        <td>{{ $product->stock }} {{ $product->unit }}</td>
                        <td><span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
