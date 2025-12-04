@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Produk</h2>
            <small class="text-muted">Kelola data produk di toko ecommerce Anda</small>
        </div>
        <div>
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                + Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                        alt="{{ $product->name }}"
                                        style="height: 50px;">
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-end">
                                <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <form action="{{ route('dashboard.products.destroy', $product) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada produk. Tambahkan produk pertama Anda.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
