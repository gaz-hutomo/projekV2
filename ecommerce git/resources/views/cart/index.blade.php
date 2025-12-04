@extends('layouts.frontend')

@section('title', 'Keranjang Belanja')

@section('content')
    <h3 class="mb-3">Keranjang Belanja</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($cart->items->count() === 0)
        <div class="alert alert-info">
            Keranjang kamu masih kosong.
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-primary">
            Mulai Belanja
        </a>
    @else
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cart->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image_path)
                                                    <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                         alt="{{ $item->product->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         class="me-2 rounded">
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                                    </div>
                                                    @if($item->product)
                                                        <div class="text-muted small">
                                                            Stok: {{ $item->product->stock }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            Rp {{ number_format($item->price_at_add, 0, ',', '.') }}
                                        </td>
                                        <td style="width: 120px;">
                                            <form action="{{ route('cart.update', $item) }}" method="POST"
                                                  class="d-flex gap-1">
                                                @csrf
                                                <input type="number" name="qty" min="0"
                                                       value="{{ $item->quantity }}"
                                                       class="form-control form-control-sm">
                                                <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                    Update
                                                </button>
                                            </form>
                                            <small class="text-muted">Isi 0 untuk hapus</small>
                                        </td>
                                        <td>
                                            Rp {{ number_format($item->price_at_add * $item->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST"
                                                  onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 text-end">
                            <form action="{{ route('cart.clear') }}" method="POST"
                                  onsubmit="return confirm('Kosongkan keranjang?');">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total</span>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                                @auth
                                    Lanjut ke Checkout
                                @else
                                    Login untuk Checkout
                                @endauth
                            </a>
                        <a href="{{ route('shop.index') }}" class="btn btn-link w-100 mt-2">
                            &larr; Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
