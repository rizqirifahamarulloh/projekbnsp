<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\AdminBookController;
use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminContactController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — BookWise BNSP
|--------------------------------------------------------------------------
| [BNSP: Membuat Kode Program Aplikasi]
|
| Semua endpoint menggunakan prefix /api secara otomatis.
| Format respons standar: { success, message, data, meta }
*/

// ═══════════════════════════════════════════
// ENDPOINT PUBLIK (tanpa auth)
// ═══════════════════════════════════════════

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Buku (publik)
Route::get('/books',        [BookController::class, 'index']);
Route::get('/books/{slug}', [BookController::class, 'show']);

// Kategori (publik)
Route::get('/categories',              [CategoryController::class, 'index']);
Route::get('/categories/{slug}/books', [CategoryController::class, 'books']);

// Kontak (publik)
Route::post('/contact', [ContactController::class, 'store']);

// Webhook Midtrans (publik — dipanggil dari server Midtrans)
Route::post('/orders/notification', [OrderController::class, 'notification']);


// ═══════════════════════════════════════════
// ENDPOINT AUTH USER (butuh login)
// ═══════════════════════════════════════════
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);

    // Keranjang
    Route::get('/cart',         [CartController::class, 'index']);
    Route::post('/cart',        [CartController::class, 'store']);
    Route::patch('/cart/{id}',  [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // Pesanan
    Route::get('/orders',              [OrderController::class, 'index']);
    Route::post('/orders',             [OrderController::class, 'store']);
    Route::get('/orders/{order_code}', [OrderController::class, 'show']);
});


// ═══════════════════════════════════════════
// ENDPOINT ADMIN (butuh login + role admin)
// ═══════════════════════════════════════════
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // CRUD Buku
    Route::get('/books',         [AdminBookController::class, 'index']);
    Route::post('/books',        [AdminBookController::class, 'store']);
    Route::get('/books/{id}',    [AdminBookController::class, 'show']);
    Route::put('/books/{id}',    [AdminBookController::class, 'update']);
    Route::delete('/books/{id}', [AdminBookController::class, 'destroy']);

    // CRUD Kategori
    Route::get('/categories',             [AdminCategoryController::class, 'index']);
    Route::post('/categories',            [AdminCategoryController::class, 'store']);
    Route::get('/categories/{id}',        [AdminCategoryController::class, 'show']);
    Route::put('/categories/{id}',        [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{id}',     [AdminCategoryController::class, 'destroy']);

    // Pengguna
    Route::get('/users', [AdminUserController::class, 'index']);

    // Pesanan
    Route::get('/orders',              [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}',         [AdminOrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    // Kontak
    Route::get('/contacts',              [AdminContactController::class, 'index']);
    Route::patch('/contacts/{id}/read',  [AdminContactController::class, 'markAsRead']);
});
