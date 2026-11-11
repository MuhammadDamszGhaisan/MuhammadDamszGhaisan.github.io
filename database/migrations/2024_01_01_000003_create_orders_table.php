<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('note')->nullable();
            $table->decimal('total', 14, 2)->default(0);
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('payment_method', ['transfer', 'cod'])->default('transfer');
            $table->enum('payment_status', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])->default('belum_bayar');
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
