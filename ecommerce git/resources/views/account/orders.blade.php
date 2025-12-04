@extends('layouts.frontend')

@section('title', 'Riwayat Pesanan')

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
                            <a href="{{ route('account.orders') }}" class="fw-bold">
                                Riwayat Pesanan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h3 class="mb-3">Riwayat Pesanan</h3>

            @if($orders->count() === 0)
                <div class="alert alert-info">
                    Kamu belum memiliki pesanan.
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Order</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Metode</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->order_number }}</td>
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
                                        <td>{{ $order->payment_method }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('account.orders.show', $order->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
