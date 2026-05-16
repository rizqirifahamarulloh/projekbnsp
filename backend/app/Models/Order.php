<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// [BNSP: Membuat Kode Program Aplikasi]
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'total_price',
        'status',
        'payment_method',
        'midtrans_token',
    ];

    // ═══ RELASI ═══

    /** Pesanan milik satu user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Satu pesanan memiliki banyak item */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ═══ HELPER ═══

    /** Generate kode pesanan unik: INV-YYYYMMDD-XXXX */
    public static function generateOrderCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "INV-{$date}-{$random}";
    }
}
