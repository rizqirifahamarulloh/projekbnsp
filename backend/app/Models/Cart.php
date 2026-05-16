<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// [BNSP: Membuat Kode Program Aplikasi]
class Cart extends Model
{
    protected $fillable = ['user_id', 'book_id', 'quantity'];

    // ═══ RELASI ═══

    /** Item keranjang milik satu user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Item keranjang merujuk ke satu buku */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
