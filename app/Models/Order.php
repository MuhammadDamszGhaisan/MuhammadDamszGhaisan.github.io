<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'note',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
        'delivered_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public const STATUS_LABELS = [
        'menunggu' => 'Menunggu Konfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public const PAYMENT_LABELS = [
        'belum_bayar' => 'Belum Bayar',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'lunas' => 'Lunas',
    ];

    public const NEXT_STEPS = [
        'menunggu' => 'Pesanan Anda akan segera dikonfirmasi dan diproses oleh admin.',
        'diproses' => 'Pesanan sedang disiapkan dan selanjutnya akan segera dikirim.',
        'dikirim' => 'Pesanan sedang dalam perjalanan menuju alamat Anda.',
        'selesai' => 'Pesanan telah selesai. Terima kasih telah berbelanja!',
        'dibatalkan' => 'Pesanan dibatalkan. Silakan hubungi kami jika ada pertanyaan.',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getPaymentLabelAttribute(): string
    {
        return self::PAYMENT_LABELS[$this->payment_status] ?? $this->payment_status;
    }

    public function getNextStepAttribute(): string
    {
        return self::NEXT_STEPS[$this->status] ?? '';
    }

    public function getRupiahTotalAttribute(): string
    {
        return 'Rp '.number_format((float) $this->total, 0, ',', '.');
    }

    public static function generateInvoiceNumber(): string
    {
        return 'INV-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
    }
}
