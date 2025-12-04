<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ecommerce Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CDN (biar cepat, nanti bisa dipindah ke asset lokal) --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f5f5f5;
        }
        .sidebar {
            min-height: 100vh;
            background: #111827;
            color: #fff;
        }
        .sidebar a {
            color: #9ca3af;
            text-decoration: none;
        }
        .sidebar a.active,
        .sidebar a:hover {
            color: #fff;
        }
        .sidebar .logo {
            font-weight: 700;
            font-size: 1.3rem;
        }
        .card-stat {
            border-radius: 12px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2 sidebar d-flex flex-column p-3">
            <div class="logo mb-4">
                🛒 MyEcommerce
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.products.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                        Produk
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.categories.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                        Kategori
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.orders.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}">
                        Pesanan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.reviews.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard.reviews.*') ? 'active' : '' }}">
                        Review
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link">
                        Pengguna
                    </a>
                </li>
            </ul>


            <hr>

            <div>
                <small>&copy; {{ date('Y') }} MyEcommerce</small>
            </div>
        </div>

        {{-- Content --}}
        <div class="col-md-10 p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
