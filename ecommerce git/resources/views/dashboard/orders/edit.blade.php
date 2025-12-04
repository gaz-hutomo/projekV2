@extends('layouts.app')

@section('title', 'Update Status Pesanan #' . $order->order_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Update Status Pesanan</h2>
            <small class="text-muted">Nomor: {{ $order->order_number }}</small>
        </div>
        <div>
            <a href="{{ route('dashboard.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                Lihat Detail
            </a>
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                &larr; Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('dashboard.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Status Order</label>
                            <select name="status" class="form-select" required>
                                @foreach($orderStatuses as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Resi (Tracking Number)</label>
                            <input type="text" name="tracking_number" class="form-control"
                                   value="{{ old('tracking_number', $order->tracking_number) }}">
                        </div>

                        @if($order->payment)
                            <div class="mb-3">
                                <label class="form-label">Status Pembayaran</label>
                                <select name="payment_status" class="form-select">
                                    @foreach($paymentStatuses as $status)
                                        <option value="{{ $status }}"
                                            {{ $order->payment->payment_status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <p class="text-muted">
                                Pesanan ini belum memiliki record pembayaran.
                            </p>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ringkasan kecil di sisi kanan --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Ringkasan Pesanan</h6>
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
                    @if($order->payment)
                        <p class="mb-1">
                            Status Pembayaran Saat Ini:
                            <strong>{{ ucfirst($order->payment->payment_status) }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
