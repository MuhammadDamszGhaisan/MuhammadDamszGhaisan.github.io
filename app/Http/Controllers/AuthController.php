<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'string'],
        ], [
            'pin.required' => 'PIN admin wajib diisi.',
        ]);

        $admin = User::where('role', 'admin')->get()
            ->first(fn (User $user) => $user->pin && Hash::check($request->pin, $user->pin));

        if (! $admin) {
            throw ValidationException::withMessages([
                'pin' => 'PIN admin salah.',
            ]);
        }

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function loginPelanggan(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $credentials['username'])
            ->where('role', 'pelanggan')
            ->first();

        if (! $user || ! Hash::check($credentials['password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.home');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', 'unique:users,username'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis bawah (_), dan tanda hubung (-).',
            'username.unique' => 'Username sudah dipakai, silakan pilih yang lain.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'role' => 'pelanggan',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pelanggan.home');
    }
}
