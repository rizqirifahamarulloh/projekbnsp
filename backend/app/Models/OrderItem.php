<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// [BNSP: Membuat Kode Program Aplikasi]
class OrderItem extends Model
{
    protected $fillable = ['order_id', 'book_id', 'quantity', 'price'];

    // ═══ RELASI ═══

    /** Item pesanan milik satu pesanan */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** Item pesanan merujuk ke satu buku */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
