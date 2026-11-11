@php $isEdit = ! is_null($product); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">-- Tanpa Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control @error('price') is-invalid @enderror" min="0" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control @error('stock') is-invalid @enderror" min="0" required>
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="form-control @error('unit') is-invalid @enderror" required>
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $product->is_active ?? true))>
            <label class="form-check-label" for="is_active">Produk Aktif</label>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Gambar Produk</label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if($isEdit && $product->image)
            <img src="{{ $product->image_url }}" class="img-fluid rounded mt-2" alt="">
        @endif
    </div>
</div>
