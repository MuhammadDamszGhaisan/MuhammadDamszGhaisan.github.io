<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2E7D32">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') &middot; @endif{{ config('app.name', 'MAGSTORE') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @include('partials.theme')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="brand-logo-wrap"><img src="{{ asset('img/logo-icon.png') }}" alt="MAGSTORE" class="brand-logo"></span> MAGSTORE
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            @auth
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @if(auth()->user()->isPelanggan())
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.products.index') }}">Produk</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pelanggan.cart.index') }}">
                                <i class="bi bi-cart" data-cart-target></i> Keranjang
                                @php $c = \App\Support\Cart::count(); @endphp
                                @if($c > 0)<span class="badge bg-warning text-dark">{{ $c }}</span>@endif
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.orders.history') }}">Riwayat</a></li>
                        <li class="nav-item dropdown">
                            @php
                                $notifUnread = auth()->user()->unreadNotifications;
                                $notifItems = auth()->user()->notifications()->latest()->take(8)->get();
                            @endphp
                            <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="bi bi-bell"></i> Notifikasi
                                @if($notifUnread->count() > 0)<span class="badge bg-danger">{{ $notifUnread->count() }}</span>@endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow notif-menu">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Notifikasi</span>
                                    @if($notifUnread->count() > 0)
                                        <form method="POST" action="{{ route('pelanggan.notifications.readAll') }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-link p-0 text-decoration-none">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @forelse($notifItems as $notif)
                                    <li>
                                        <form method="POST" action="{{ route('pelanggan.notifications.read', $notif->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item notif-item {{ $notif->read_at ? '' : 'notif-unread' }}">
                                                <span class="notif-icon"><i class="bi bi-{{ $notif->data['icon'] ?? 'bell' }}"></i></span>
                                                <span class="notif-body">
                                                    <span class="notif-title">{{ $notif->data['title'] ?? 'Notifikasi' }}</span>
                                                    <span class="notif-msg">{{ $notif->data['message'] ?? '' }}</span>
                                                    <span class="notif-time">{{ $notif->created_at->format('d/m/Y H:i') }}</span>
                                                </span>
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item-text text-muted small">Belum ada notifikasi.</span></li>
                                @endforelse
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->isPelanggan())
                                <li><a class="dropdown-item" href="{{ route('pelanggan.profile.edit') }}"><i class="bi bi-person-gear"></i> Akun Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="container my-4 app-content">
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
</main>

<footer class="site-footer">
    <div class="container">
        <span class="brand-mini">{{ config('app.name', 'MAGSTORE') }}</span> &middot;
        <span class="tagline">Smart Shopping, Easy Living.</span>
        <div class="mt-1">&copy; {{ date('Y') }} &middot; Dibuat dengan <i class="bi bi-heart-fill text-accent"></i></div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
