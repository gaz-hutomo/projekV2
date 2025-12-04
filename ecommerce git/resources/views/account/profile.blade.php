@extends('layouts.frontend')

@section('title', 'Profil Saya')

@section('content')
    <div class="row">
        <div class="col-md-3 mb-3">
            {{-- Sidebar mini akun --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Akun Saya</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('account.profile') }}" class="fw-bold">
                                Profil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.orders') }}">
                                Riwayat Pesanan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h3 class="mb-3">Profil Saya</h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
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

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="3"
                                      class="form-control">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
