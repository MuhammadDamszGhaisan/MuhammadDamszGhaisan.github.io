@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="row justify-content-center auth-wrap py-3 py-md-4">
    <div class="col-md-7 col-lg-6">
        <div class="text-center mb-4">
            <span class="auth-logo-wrap mb-3"><img src="{{ asset('img/logo-icon.png') }}" alt="MAGSTORE" class="auth-logo"></span>
            <h3 class="fw-bold mb-1">Buat Akun Baru</h3>
            <p class="text-accent fw-semibold mb-0">Smart Shopping, Easy Living.</p>
            <p class="text-muted small mb-0 mt-1">Daftar untuk mulai berbelanja</p>
        </div>
        <div class="card auth-card">
            <div class="card-body">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap Anda" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="Username" autocomplete="username" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">4&ndash;50 karakter, hanya huruf, angka, <code>_</code> dan <code>-</code>.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Alamat pengiriman" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="reg-password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" autocomplete="new-password" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reg-password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="reg-password-confirm" name="password_confirmation" class="form-control" placeholder="Ulangi password" autocomplete="new-password" required>
                                <button class="btn btn-outline-secondary" type="button" data-toggle-password="reg-password-confirm" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 py-2" type="submit"><i class="bi bi-person-plus"></i> Daftar</button>
                    <p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
