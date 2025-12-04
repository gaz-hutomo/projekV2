@php use Illuminate\Support\Str; @endphp
@extends('layouts.frontend')

@section('title', 'Katalog Produk')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">Katalog Produk</h2>
            <p class="text-muted mb-0">
                Temukan produk terbaik untuk kebutuhanmu
            </p>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('shop.index') }}" class="d-flex gap-2">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Cari produk..."
                       value="{{ $search }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Filter kategori --}}
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Kategori</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1">
                            <a href="{{ route('shop.index') }}"
                               class="{{ !$currentCategorySlug ? 'fw-bold' : '' }}">
                                Semua Produk
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="mb-1">
                                <a href="{{ route('shop.category', $category->slug) }}"
                                   class="{{ $currentCategorySlug === $category->slug ? 'fw-bold' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Grid produk --}}
        <div class="col-md-9">
            @if($products->count() === 0)
                <div class="alert alert-info">
                    Belum ada produk yang bisa ditampilkan.
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         alt="{{ $product->name }}"
                                         class="product-image">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light product-image">
                                        <span class="text-muted small">Tidak ada gambar</span>
                                    </div>
                                @endif

                                <div class="card-body">
                                    @if($product->category)
                                        <span class="badge bg-light text-dark mb-2">
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                    <h6 class="card-title">
                                        <a href="{{ route('shop.show', $product) }}"
                                           class="text-decoration-none text-dark">
                                            {{ Str::limit($product->name, 40) }}
                                        </a>
                                    </h6>
                                    <p class="mb-1 fw-semibold">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-muted mb-2 small">
                                        Stok: {{ $product->stock }}
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                    <a href="{{ route('shop.show', $product) }}"
                                    class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>

                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="qty" value="1">
                                        <button class="btn btn-sm btn-primary" type="submit">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
