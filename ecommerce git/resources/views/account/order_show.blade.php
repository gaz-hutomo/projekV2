@extends('layouts.frontend')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Akun Saya</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('account.profile') }}">
                                Profil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.orders') }}">
                                Riwayat Pesanan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Detail Pesanan</h3>
                    <p class="mb-0 text-muted">
                        Nomor Order: <strong>{{ $order->order_number }}</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary">
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Informasi Pesanan</h6>
                            <p class="mb-1">
                                Status:
                                <span class="badge 
                                    @if($order->status === 'completed') bg-success
                                    @elseif($order->status === 'pending') bg-warning text-dark
                                    @elseif($order->status === 'cancelled') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p class="mb-1">
                                Tanggal: {{ $order->created_at->format('d M Y H:i') }}
                            </p>
                            <p class="mb-1">
                                Total: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </p>
                            <p class="mb-1">
                                Metode Pembayaran: {{ $order->payment_method }}
                            </p>
                            @if($order->tracking_number)
                                <p class="mb-1">
                                    No. Resi: {{ $order->tracking_number }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Alamat Pengiriman</h6>
                            <p class="mb-0">
                                {!! nl2br(e($order->shipping_address)) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->payment)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Informasi Pembayaran</h6>
                        <p class="mb-1">
                            Status Pembayaran:
                            <span class="badge
                                @if($order->payment->payment_status === 'paid') bg-success
                                @elseif($order->payment->payment_status === 'pending') bg-warning text-dark
                                @else bg-secondary
                                @endif
                            ">
                                {{ ucfirst($order->payment->payment_status) }}
                            </span>
                        </p>
                        <p class="mb-1">
                            Jumlah: Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                        </p>
                        @if($order->payment->transaction_ref)
                            <p class="mb-1">
                                Kode Transaksi: {{ $order->payment->transaction_ref }}
                            </p>
                        @endif
                        @if($order->payment->paid_at)
                            <p class="mb-1">
                                Dibayar pada: {{ $order->payment->paid_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card-body p-0">
                    <h6 class="px-3 pt-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Review</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                @php
                                    $product = $item->product;
                                    $review  = $product ? ($userReviews[$product->id] ?? null) : null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $product->name ?? 'Produk tidak tersedia' }}
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    <td style="min-width: 220px;">
                                        @if(!$product)
                                            <span class="text-muted small">Produk tidak tersedia</span>
                                        @elseif($order->status !== 'completed')
                                            <span class="text-muted small">
                                                Review tersedia setelah pesanan selesai (completed).
                                            </span>
                                        @else
                                            {{-- Form review/update --}}
                                            <form action="{{ route('account.orders.items.review', [$order->id, $item->id]) }}"
                                                method="POST" class="small">
                                                @csrf

                                                <div class="mb-1">
                                                    <select name="rating" class="form-select form-select-sm" required>
                                                        <option value="">Rating</option>
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ old('rating', $review->rating ?? '') == $i ? 'selected' : '' }}>
                                                                {{ $i }} / 5
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="mb-1">
                                                    <textarea name="comment" rows="2" class="form-control form-control-sm"
                                                            placeholder="Komentar (opsional)">{{ old('comment', $review->comment ?? '') }}</textarea>
                                                </div>

                                                <button class="btn btn-sm btn-primary" type="submit">
                                                    {{ $review ? 'Update Review' : 'Kirim Review' }}
                                                </button>
                                            </form>

                                            @if($review)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        Review kamu: {{ $review->rating }}/5
                                                        @if($review->comment)
                                                            – "{{ $review->comment }}"
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="p-3 text-end">
                        <strong>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
