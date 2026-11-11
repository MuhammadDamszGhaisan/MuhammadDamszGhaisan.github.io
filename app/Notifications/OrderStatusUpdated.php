<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $title,
        public string $message,
        public string $icon = 'bell',
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'invoice_number' => $this->order->invoice_number,
            'status' => $this->order->status,
            'payment_status' => $this->order->payment_status,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => route('pelanggan.orders.status', $this->order->id),
        ];
    }

    /**
     * Build a notification describing an order status change.
     */
    public static function forStatus(Order $order): self
    {
        [$title, $message, $icon] = match ($order->status) {
            'menunggu' => [
                'Pesanan Diterima',
                'Pesanan '.$order->invoice_number.' sudah kami terima dan sedang menunggu konfirmasi.',
                'receipt',
            ],
            'diproses' => [
                'Pesanan Sedang Diproses',
                'Pesanan '.$order->invoice_number.' sedang diproses. Selanjutnya akan segera dikirim.',
                'gear',
            ],
            'dikirim' => [
                'Pesanan Sedang Dikirim',
                'Pesanan '.$order->invoice_number.' sedang dalam perjalanan ke alamat Anda.',
                'truck',
            ],
            'selesai' => [
                'Pesanan Selesai',
                'Pesanan '.$order->invoice_number.' telah selesai. Terima kasih telah berbelanja!',
                'check-circle',
            ],
            'dibatalkan' => [
                'Pesanan Dibatalkan',
                'Pesanan '.$order->invoice_number.' telah dibatalkan.',
                'x-circle',
            ],
            default => [
                'Status Pesanan Diperbarui',
                'Status pesanan '.$order->invoice_number.' kini: '.$order->status_label.'.',
                'bell',
            ],
        };

        return new self($order, $title, $message, $icon);
    }

    /**
     * Build a notification for a verified (paid) order.
     */
    public static function paymentVerified(Order $order): self
    {
        return new self(
            $order,
            'Pembayaran Diverifikasi',
            'Pembayaran pesanan '.$order->invoice_number.' telah diverifikasi (Lunas).',
            'credit-card',
        );
    }
}
