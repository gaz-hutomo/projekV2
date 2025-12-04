<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Toko Online')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CDN --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f9fafb;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .product-card {
            border-radius: 12px;
            transition: box-shadow .2s, transform .2s;
        }
        .product-card:hover {
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
            transform: translateY(-2px);
        }
        .product-image {
            object-fit: cover;
            height: 200px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        footer {
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop.index') }}">🛒 MyEcommerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">Keranjang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Admin</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('account.profile') }}">
                            Halo, {{ auth()->user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="display:inline; cursor:pointer;">
                                Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>

<footer class="py-3 bg-white mt-4">
    <div class="container d-flex justify-content-between">
        <span class="text-muted">© {{ date('Y') }} MyEcommerce</span>
        <span class="text-muted small">Powered by Laravel</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
