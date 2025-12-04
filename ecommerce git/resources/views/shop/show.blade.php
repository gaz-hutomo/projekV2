@extends('layouts.frontend')

@section('title', $product->name . ' - Detail Produk')

@section('content')
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="text-decoration-none">&larr; Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light"
                         style="height: 320px; border-radius: 12px;">
                        <span class="text-muted">Tidak ada gambar</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="mb-2">{{ $product->name }}</h2>

            @if($averageRating)
                <p class="mb-1">
                    Rating: {{ number_format($averageRating, 1) }} / 5
                    <span class="text-muted small">({{ $product->reviews->count() }} ulasan)</span>
                </p>
            @endif

            @if($product->category)
                <p class="mb-2">
                    <span class="badge bg-light text-dark">
                        {{ $product->category->name }}
                    </span>
                </p>
            @endif

            <p class="fs-4 fw-semibold text-primary mb-2">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <p class="text-muted mb-2">
                SKU: {{ $product->sku }}
            </p>

            <p class="mb-2">
                Stok:
                @if($product->stock > 0)
                    <span class="text-success fw-semibold">{{ $product->stock }}</span>
                @else
                    <span class="text-danger fw-semibold">Habis</span>
                @endif
            </p>

            <div class="my-3">
                <p class="mb-1 fw-semibold">Deskripsi Produk</p>
                <p class="text-muted">
                    {{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}
                </p>
            </div>
            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-2">
                @csrf
                <div style="width: 100px;">
                    <input type="number" name="qty" min="1" value="1" class="form-control">
                </div>
                <button class="btn btn-primary" type="submit">
                    Add to Cart
                </button>
                <button class="btn btn-outline-secondary" type="button" disabled>
                    Beli Sekarang
                </button>
            </form>
        </div>
    </div>


        <hr class="my-4">
        <h5 class="mb-3">Ulasan Produk</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h2 class="mb-2">{{ $product->name }}</h2>

        @if($averageRating)
            <p class="mb-1">
                Rating: {{ number_format($averageRating, 1) }} / 5
                <span class="text-muted small">({{ $product->reviews->count() }} ulasan)</span>
            </p>
        @endif


        {{-- Daftar ulasan --}}
        @if($product->reviews->count() > 0)
            @foreach($product->reviews->sortByDesc('created_at') as $review)
                <div class="border rounded p-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review->user->name ?? 'User' }}</strong>
                        <span class="text-muted small">
                            {{ $review->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <div class="small mb-1">
                        Rating: {{ $review->rating }} / 5
                    </div>
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
        @endif


    {{-- Produk terkait --}}
    @if($relatedProducts->count() > 0)
        <hr class="my-4">
        <h5 class="mb-3">Produk Terkait</h5>
        <div class="row g-3">
            @foreach($relatedProducts as $item)
                <div class="col-md-3">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}"
                                 alt="{{ $item->name }}"
                                 class="product-image">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light product-image">
                                <span class="text-muted small">Tidak ada gambar</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{ route('shop.show', $item) }}"
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($item->name, 35) }}
                                </a>
                            </h6>
                            <p class="mb-0 fw-semibold">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
