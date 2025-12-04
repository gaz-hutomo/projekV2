@csrf

<div class="mb-3">
    <label class="form-label">Nama Produk</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">SKU</label>
    <input type="text" name="sku" class="form-control"
           value="{{ old('sku', $product->sku ?? '') }}" required>
    @error('sku')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="category_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Harga</label>
        <input type="number" step="0.01" name="price" class="form-control"
               value="{{ old('price', $product->price ?? '') }}" required>
        @error('price')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" class="form-control"
               value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Gambar Produk</label>
    <input type="file" name="image" class="form-control">

    @error('image')
        <div class="text-danger small">{{ $message }}</div>
    @enderror

    @isset($product)
        @if(!empty($product->image_path))
            <div class="mt-2">
                <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                <img src="{{ asset('storage/' . $product->image_path) }}"
                    alt="Gambar produk"
                    style="max-height: 120px;">
            </div>
        @endif
    @endisset
</div>


<div class="d-flex justify-content-between">
    <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary">
        Kembali
    </a>
    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</div>
