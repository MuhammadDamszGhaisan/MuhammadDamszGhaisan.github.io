@extends('layouts.admin')

@section('title', 'Akun Admin')

@section('content')
<div class="page-title mb-4">
    <span class="pi"><i class="bi bi-shield-lock"></i></span>
    <span>Akun Admin<small>Ganti PIN untuk masuk ke panel admin</small></span>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-person-circle"></i> Informasi Akun</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama</dt>
                    <dd class="col-sm-8">{{ $user->name }}</dd>
                    <dt class="col-sm-4">Username</dt>
                    <dd class="col-sm-8">{{ $user->username }}</dd>
                    <dt class="col-sm-4">Peran</dt>
                    <dd class="col-sm-8"><span class="badge bg-primary">Admin</span></dd>
                </dl>
                <hr>
                <div class="small text-muted">
                    <i class="bi bi-info-circle"></i> PIN ini dipakai untuk login admin di halaman
                    <code>/login</code>. Jangan bagikan PIN kepada siapa pun.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-key"></i> Ganti PIN Admin</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.pin') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">PIN Lama</label>
                        <div class="input-group">
                            <input type="password" id="pin-current" name="current_pin" inputmode="numeric" class="form-control @error('current_pin') is-invalid @enderror" autocomplete="current-password" required>
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="pin-current" aria-label="Tampilkan PIN"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('current_pin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PIN Baru</label>
                        <div class="input-group">
                            <input type="password" id="pin-new" name="pin" inputmode="numeric" pattern="[0-9]{6,20}" class="form-control @error('pin') is-invalid @enderror" autocomplete="new-password" required>
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="pin-new" aria-label="Tampilkan PIN"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('pin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div class="form-text">6&ndash;20 digit angka. Gunakan PIN yang mudah Anda ingat tetapi sulit ditebak.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ulangi PIN Baru</label>
                        <input type="password" name="pin_confirmation" inputmode="numeric" pattern="[0-9]{6,20}" class="form-control" autocomplete="new-password" required>
                    </div>

                    <button class="btn btn-primary"><i class="bi bi-shield-check"></i> Simpan PIN Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
