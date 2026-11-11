@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-tags"></i> Kategori Produk</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory"><i class="bi bi-plus-lg"></i> Tambah Kategori</button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ $category->description ?: '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $category->products_count }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit{{ $category->id }}"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="edit{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="modal-content">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body">
                                    <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" value="{{ $category->name }}" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Deskripsi</label><input type="text" name="description" value="{{ $category->description }}" class="form-control"></div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $categories->links() }}</div>

<div class="modal fade" id="addCategory" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Deskripsi</label><input type="text" name="description" class="form-control"></div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>
@endsection
