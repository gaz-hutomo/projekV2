@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Pesanan</h2>
            <small class="text-muted">Kelola pesanan pelanggan</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nomor Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status Order</th>
                        <th>Status Pembayaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status === 'completed') bg-success
                                    @elseif($order->status === 'pending') bg-warning text-dark
                                    @elseif($order->status === 'cancelled') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment)
                                    <span class="badge
                                        @if($order->payment->payment_status === 'paid') bg-success
                                        @elseif($order->payment->payment_status === 'pending') bg-warning text-dark
                                        @elseif($order->payment->payment_status === 'failed') bg-danger
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ ucfirst($order->payment->payment_status) }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Detail
                                </a>
                                <a href="{{ route('dashboard.orders.edit', $order) }}"
                                   class="btn btn-sm btn-warning">
                                    Update Status
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
