@extends('layouts.frontend')

@section('title', 'Checkout')

@section('content')
    <h3 class="mb-3">Checkout</h3>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        {{-- Form pengiriman & pembayaran --}}
        <div class="col-md-7 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Data Pengiriman</h5>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea name="shipping_address" rows="4" class="form-control" required>{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                    Transfer Bank
                                </option>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                                    Bayar di Tempat (COD)
                                </option>
                                <option value="ewallet" {{ old('payment_method') == 'ewallet' ? 'selected' : '' }}>
                                    E-Wallet
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-link">
                            &larr; Kembali ke Keranjang
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ringkasan order --}}
        <div class="col-md-5 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Ringkasan Pesanan</h5>

                    <ul class="list-group mb-3">
                        @foreach($cart->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                    </div>
                                    <small class="text-muted">
                                        Qty: {{ $item->quantity }} x Rp {{ number_format($item->price_at_add, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div>
                                    Rp {{ number_format($item->price_at_add * $item->quantity, 0, ',', '.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>

                    <small class="text-muted">
                        * Setelah klik "Buat Pesanan", pesanan akan tercatat dan kamu bisa lihat di menu Riwayat Pesanan.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
