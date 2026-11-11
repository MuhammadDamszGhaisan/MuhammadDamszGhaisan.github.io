@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-person-gear text-primary fs-5"></i>
    <div>
        <h4 class="section-heading mb-0">Akun Saya</h4>
        <small class="text-muted">Kelola username, data diri, dan password Anda sendiri.</small>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-person-vcard"></i> Data Akun</div>
            <div class="card-body">
                <form method="POST" action="{{ route('pelanggan.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">4&ndash;50 karakter, hanya huruf, angka, <code>_</code> dan <code>-</code>.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat pengiriman">{{ old('address', $user->address) }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-shield-lock"></i> Ganti Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('pelanggan.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" id="pw-current" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required>
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="pw-current" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" id="pw-new" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="pw-new" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div class="form-text">Minimal 6 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ulangi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>

                    <button class="btn btn-outline-primary"><i class="bi bi-key"></i> Ganti Password</button>
                </form>

                <hr>
                <div class="small text-muted">
                    <i class="bi bi-info-circle"></i> Setelah mengganti password, gunakan password baru pada login berikutnya. Tidak perlu menghubungi admin.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
