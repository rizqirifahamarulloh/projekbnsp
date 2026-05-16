<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Panel Admin (Server-Side Rendered)
|--------------------------------------------------------------------------
| [BNSP: Membuat Antarmuka Pengguna]
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ═══ Admin Auth ═══
// Route 'login' wajib ada — digunakan middleware auth untuk redirect
Route::get('/admin/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Auto-login dari React → Admin Panel (menggunakan Sanctum token)
Route::get('/admin/auto-login', [AuthController::class, 'autoLogin'])->name('admin.autoLogin');

// ═══ Admin Panel (butuh auth + role admin) ═══
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');

    // Kategori CRUD
    Route::resource('categories', CategoryController::class);

    // Buku CRUD
    Route::resource('books', BookController::class);

    // Pengguna (read only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Pesanan
    Route::get('/orders',              [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}',         [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Kontak
    Route::get('/contacts',              [ContactController::class, 'index'])->name('contacts.index');
    Route::patch('/contacts/{id}/read',  [ContactController::class, 'markAsRead'])->name('contacts.markAsRead');
});
