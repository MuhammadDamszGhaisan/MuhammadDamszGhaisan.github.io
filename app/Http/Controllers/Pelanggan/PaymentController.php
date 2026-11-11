<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        return view('pelanggan.payment.show', compact('order'));
    }

    public function upload(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'payment_proof.required' => 'Silakan pilih foto bukti pembayaran terlebih dahulu.',
            'payment_proof.image' => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'payment_proof.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'payment_proof.max' => 'Ukuran foto maksimal 10 MB. Perkecil atau kompres fotonya terlebih dahulu.',
            'payment_proof.uploaded' => 'Foto gagal diunggah (mungkin terlalu besar). Coba foto lain atau perkecil ukurannya.',
        ]);

        $file = $request->file('payment_proof');

        if (! $file || ! $file->isValid()) {
            return back()->withErrors([
                'payment_proof' => 'Foto gagal diunggah. Silakan coba lagi atau gunakan foto lain.',
            ]);
        }

        try {
            if ($order->payment_proof) {
                Storage::disk('uploads')->delete($order->payment_proof);
            }

            $path = $file->store('payment_proofs', 'uploads');
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'payment_proof' => 'Gagal menyimpan foto bukti pembayaran. Silakan coba lagi.',
            ]);
        }

        if (! $path) {
            return back()->withErrors([
                'payment_proof' => 'Gagal menyimpan foto bukti pembayaran. Silakan coba lagi.',
            ]);
        }

        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('pelanggan.orders.status', $order)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_if($order->user_id !== auth()->id(), 403);
    }
}
