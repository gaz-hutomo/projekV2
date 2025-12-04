@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Tambah Produk</h2>
            <small class="text-muted">Isi data produk baru</small>
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
            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
                @include('products._form')
            </form>
        </div>
    </div>
@endsection
