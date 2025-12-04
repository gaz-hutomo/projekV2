@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Produk</h2>
            <small class="text-muted">Perbarui data produk</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('products._form', ['product' => $product])
            </form>
        </div>
    </div>
@endsection
