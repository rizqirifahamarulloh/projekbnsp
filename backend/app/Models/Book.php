<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// [BNSP: Membuat Kode Program Aplikasi]
class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'publisher',
        'year',
        'price',
        'stock',
        'cover_image',
        'description',
    ];

    // ═══ RELASI ═══

    /** Buku milik satu kategori */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Buku bisa ada di banyak keranjang */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /** Buku bisa ada di banyak order item */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ═══ EVENT ═══

    /** Otomatis generate slug dari judul buku */
    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title);
            }
        });
    }

    // ═══ ACCESSOR ═══

    /** URL lengkap untuk cover image */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
    }
}
