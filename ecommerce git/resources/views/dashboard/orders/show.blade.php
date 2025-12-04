@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Detail Pesanan</h2>
            <small class="text-muted">Nomor: {{ $order->order_number }}</small>
        </div>
        <div>
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                &larr; Kembali
            </a>
            <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-warning">
                Update Status
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Informasi Pesanan</h6>
                    <p class="mb-1">
                        Pelanggan: {{ $order->user->name ?? '-' }}<br>
                        Email: {{ $order->user->email ?? '-' }}
                    </p>
                    <p class="mb-1">
                        Tanggal: {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                    <p class="mb-1">
                        Total: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </p>
                    <p class="mb-1">
                        Status Order:
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
                        Metode Pembayaran: {{ $order->payment_method }}
                    </p>
                    @if($order->tracking_number)
                        <p class="mb-1">
                            Resi: {{ $order->tracking_number }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Alamat Pengiriman</h6>
                    <p class="mb-0">{!! nl2br(e($order->shipping_address)) !!}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Pembayaran</h6>
                    @if($order->payment)
                        <p class="mb-1">
                            Status:
                            <span class="badge
                                @if($order->payment->payment_status === 'paid') bg-success
                                @elseif($order->payment->payment_status === 'pending') bg-warning text-dark
                                @elseif($order->payment->payment_status === 'failed') bg-danger
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
                                Ref: {{ $order->payment->transaction_ref }}
                            </p>
                        @endif
                        @if($order->payment->paid_at)
                            <p class="mb-1">
                                Dibayar: {{ $order->payment->paid_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    @else
                        <p class="text-muted mb-0">Belum ada data pembayaran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Item order --}}
    <div class="card border-0 shadow-sm">
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
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Produk tidak tersedia' }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
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
@endsection
