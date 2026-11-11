@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
<h4 class="mb-3"><i class="bi bi-plus-circle"></i> Tambah Produk</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form', ['product' => null])
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
