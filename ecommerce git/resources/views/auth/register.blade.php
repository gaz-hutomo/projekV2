@extends('layouts.frontend')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="mb-3 text-center">Daftar Akun</h3>

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
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Daftar
                        </button>
                    </form>

                    <p class="mt-3 mb-0 text-center">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
