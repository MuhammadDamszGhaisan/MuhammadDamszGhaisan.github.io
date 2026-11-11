<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('pelanggan.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => [
                'required', 'string', 'min:4', 'max:50', 'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis bawah (_), dan tanda hubung (-).',
            'username.unique' => 'Username sudah dipakai, silakan pilih yang lain.',
            'phone.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ]);

        $user->update($data);

        return back()->with('success', 'Data akun berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        if (! Hash::check($data['current_password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diganti. Gunakan password baru saat login berikutnya.');
    }
}
