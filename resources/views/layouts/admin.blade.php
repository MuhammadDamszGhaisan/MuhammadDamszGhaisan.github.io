<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2E7D32">
    <title>@hasSection('title')@yield('title') &middot; @endif{{ config('app.name', 'MAGSTORE') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @include('partials.theme')
    {{-- CSS layout admin (dipisah ke file) : public/css/admin.css --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="sidebar d-flex flex-column">
    <a class="brand d-flex align-items-center gap-2 p-3 text-decoration-none border-bottom" style="border-color: rgba(255,255,255,.12) !important;" href="{{ route('admin.dashboard') }}">
        <span class="brand-logo-wrap"><img src="{{ asset('img/logo-icon.png') }}" alt="MAGSTORE" class="brand-logo"></span>
        <span class="fs-5">MAGSTORE</span>
    </a>
    <ul class="nav nav-pills flex-column mt-2">
        @php $r = Route::currentRouteName(); @endphp
        <li class="nav-item"><a class="nav-link {{ $r === 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i> Kelola Pesanan</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.products') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Kelola Produk</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.categories') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Kategori</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.stocks') ? 'active' : '' }}" href="{{ route('admin.stocks.index') }}"><i class="bi bi-boxes"></i> Kelola Stok & Harga</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}"><i class="bi bi-people"></i> Kelola Pelanggan</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.transactions') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}"><i class="bi bi-cash-coin"></i> Transaksi & Struk</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="bi bi-graph-up"></i> Laporan Penjualan</a></li>
        <li class="nav-item"><a class="nav-link {{ str_starts_with($r, 'admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}"><i class="bi bi-shield-lock"></i> Akun Admin</a></li>
    </ul>
    <div class="mt-auto p-3">
        <a class="text-white-50 small text-decoration-none" href="{{ route('admin.profile.edit') }}" title="Buka Akun Admin">
            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
        </a>
    </div>
</div>

<div class="content">
    <div class="admin-topbar d-flex align-items-center justify-content-between px-4 py-3">
        <div class="fw-semibold"><i class="bi bi-shield-lock me-1"></i> Panel Admin</div>
        <div class="d-flex align-items-center gap-3">
            <span class="small text-white-50 d-none d-sm-inline">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <main class="container-fluid p-4 app-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

        <footer class="site-footer">
            <span class="brand-mini">{{ config('app.name', 'MAGSTORE') }}</span> &middot; Panel Admin &middot;
            <span class="tagline">Smart Shopping, Easy Living.</span>
            <div class="mt-1">&copy; {{ date('Y') }}</div>
        </footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
