@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center auth-wrap py-3 py-md-5">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <span class="auth-logo-wrap mb-3"><img src="{{ asset('img/logo-icon.png') }}" alt="MAGSTORE" class="auth-logo"></span>
            <h3 class="fw-bold mb-1">MAGSTORE</h3>
            <p class="text-accent fw-semibold mb-0">Smart Shopping, Easy Living.</p>
            <p class="text-muted small mb-0 mt-1">Silakan masuk sesuai peran Anda</p>
        </div>

        <div class="card auth-card">
            <div class="card-body">
                <ul class="nav nav-pills nav-justified mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-pelanggan" type="button">
                            <i class="bi bi-people"></i> Pelanggan
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-admin" type="button">
                            <i class="bi bi-shield-lock"></i> Admin / Kasir
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pelanggan">
                        <form method="POST" action="{{ route('login.pelanggan') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan username" required autofocus>
                                </div>
                                @error('username') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" id="login-password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" autocomplete="current-password" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="login-password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button class="btn btn-primary w-100 py-2" type="submit"><i class="bi bi-box-arrow-in-right"></i> Masuk sebagai Pelanggan</button>
                        </form>
                        <p class="text-center mt-3 mb-0 small">
                            Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar di sini</a>
                        </p>
                    </div>

                    <div class="tab-pane fade" id="tab-admin">
                        <form method="POST" action="{{ route('login.admin') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">PIN Admin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-key text-muted"></i></span>
                                    <input type="password" id="login-pin" inputmode="numeric" name="pin" class="form-control @error('pin') is-invalid @enderror" placeholder="Masukkan PIN" required>
                                    <button class="btn btn-outline-secondary" type="button" data-toggle-password="login-pin" aria-label="Tampilkan PIN"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('pin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button class="btn btn-dark w-100 py-2" type="submit"><i class="bi bi-shield-lock"></i> Masuk sebagai Admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
