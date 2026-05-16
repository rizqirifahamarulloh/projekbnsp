<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// [BNSP: Membuat Kode Program Aplikasi]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ═══ RELASI ═══

    /** Satu user memiliki banyak item keranjang */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /** Satu user memiliki banyak pesanan */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ═══ HELPER ═══

    /** Cek apakah user adalah admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
