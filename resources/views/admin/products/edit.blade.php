@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
<h4 class="mb-3"><i class="bi bi-pencil-square"></i> Edit Produk</h4>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.products._form', ['product' => $product])
            <button class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection
