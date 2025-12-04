@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Kategori</h2>
            <small class="text-muted">Perbarui nama kategori</small>
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
            <form action="{{ route('dashboard.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('categories._form', ['category' => $category])
            </form>
        </div>
    </div>
@endsection
