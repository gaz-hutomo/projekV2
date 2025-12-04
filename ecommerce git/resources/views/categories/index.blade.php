@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Kategori</h2>
            <small class="text-muted">Kelola kategori produk di toko Anda</small>
        </div>
        <div>
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">
               + Tambah Kategori
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td class="text-end">
                                <a href="{{ route('dashboard.categories.edit', $category) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <form action="{{ route('dashboard.categories.destroy', $category) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada kategori. Tambahkan kategori pertama Anda.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
