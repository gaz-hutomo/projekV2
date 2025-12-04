@php use Illuminate\Support\Str; @endphp

@extends('layouts.app')

@section('title', 'Manajemen Review')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Review Produk</h2>
            <small class="text-muted">Moderasi ulasan dari pelanggan</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control"
                   placeholder="Cari produk / user / komentar"
                   value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="rating" class="form-select">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} / 5
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit">
                Filter
            </button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>
                                @if($review->product)
                                    <a href="{{ route('shop.show', $review->product) }}" target="_blank">
                                        {{ $review->product->name }}
                                    </a>
                                @else
                                    <span class="text-muted small">Produk tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                {{ $review->user->name ?? 'User' }}<br>
                                <small class="text-muted">{{ $review->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $review->rating }} / 5</td>
                            <td style="max-width: 280px;">
                                <span title="{{ $review->comment }}">
                                    {{ \Illuminate\Support\Str::limit($review->comment, 80) }}
                                </span>
                            </td>
                            <td>{{ $review->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('dashboard.reviews.destroy', $review) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus review ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada review.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection
