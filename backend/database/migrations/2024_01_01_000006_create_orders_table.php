<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// [BNSP: Menggunakan Basis Data]
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_code')->unique(); // Kode unik pesanan, contoh: INV-20240101-XXXX
            $table->integer('total_price');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // midtrans / cod
            $table->string('midtrans_token')->nullable(); // Token untuk Snap.js
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('price'); // Harga satuan saat checkout (snapshot)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
