@extends('layouts.app')

@section('title', 'Dashboard Ecommerce')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Dashboard Ecommerce</h2>
            <small class="text-muted">Ringkasan data penjualan & aktivitas toko</small>
        </div>
        <div>
            {{-- Nanti bisa diisi tombol "Tambah Produk", dsb --}}
        </div>
    </div>

    {{-- Row statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Pengguna</p>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Produk</p>
                    <h3 class="mb-0">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Pesanan</p>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Pendapatan</p>
                    <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Row bawah: kategori & pesanan terbaru --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Ringkasan Lainnya</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Total Kategori:</span>
                        <strong>{{ $totalCategories }}</strong>
                    </p>

                    {{-- Bisa ditambah info lain, misalnya:
                    <p class="mb-2">
                        <span class="text-muted">Order Pending:</span>
                        <strong>{{ $pendingOrders }}</strong>
                    </p>
                    --}}
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Pesanan Terbaru</h6>
                    {{-- nanti bisa tambah link "Lihat semua" --}}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status === 'completed') bg-success
                                            @elseif($order->status === 'pending') bg-warning text-dark
                                            @else bg-secondary
                                            @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Belum ada pesanan.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
