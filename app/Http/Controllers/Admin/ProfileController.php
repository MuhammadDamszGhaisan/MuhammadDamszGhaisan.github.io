<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function updatePin(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_pin' => ['required', 'string', 'max:20'],
            'pin' => ['required', 'string', 'digits_between:6,20', 'confirmed', 'different:current_pin'],
        ], [
            'current_pin.required' => 'PIN lama wajib diisi.',
            'current_pin.max' => 'PIN lama maksimal 20 karakter.',
            'pin.required' => 'PIN baru wajib diisi.',
            'pin.digits_between' => 'PIN baru harus berupa angka, 6–20 digit.',
            'pin.confirmed' => 'Konfirmasi PIN baru tidak sama.',
            'pin.different' => 'PIN baru harus berbeda dari PIN lama.',
        ]);

        if (! $user->pin || ! Hash::check($data['current_pin'], $user->pin)) {
            throw ValidationException::withMessages([
                'current_pin' => 'PIN lama tidak cocok.',
            ]);
        }

        $user->pin = Hash::make($data['pin']);
        $user->save();

        return back()->with('success', 'PIN admin berhasil diganti. Gunakan PIN baru saat login berikutnya.');
    }
}
