<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// [BNSP: Membuat Kode Program Aplikasi]
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    // ═══ RELASI ═══

    /** Satu kategori memiliki banyak buku */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // ═══ EVENT ═══

    /** Otomatis generate slug dari nama kategori */
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
