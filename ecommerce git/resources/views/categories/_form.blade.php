@csrf

<div class="mb-3">
    <label class="form-label">Nama Kategori</label>
    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $category->name ?? '') }}"
           required>
    @error('name')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">
        Kembali
    </a>
    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</div>
