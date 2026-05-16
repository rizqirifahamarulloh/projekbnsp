<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Controller untuk keranjang belanja (auth required)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class CartController extends Controller
{
    use ApiResponse;

    /** GET /api/cart — Menampilkan isi keranjang user */
    public function index(Request $request)
    {
        $carts = $request->user()
            ->carts()
            ->with('book.category')
            ->get();

        // Hitung total harga semua item di keranjang
        $totalPrice = $carts->sum(fn($cart) => $cart->quantity * $cart->book->price);

        return $this->successResponse([
            'items'       => CartResource::collection($carts),
            'total_items' => $carts->sum('quantity'),
            'total_price' => $totalPrice,
        ], 'Keranjang berhasil diambil');
    }

    /** POST /api/cart — Menambahkan buku ke keranjang */
    public function store(StoreCartRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        // Cek stok
        if ($book->stock < $request->quantity) {
            return $this->errorResponse('Stok buku tidak mencukupi.', 422);
        }

        // Jika buku sudah ada di keranjang, tambahkan quantity-nya
        $cart = Cart::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'book_id' => $request->book_id,
            ],
            [
                'quantity' => \DB::raw('quantity + ' . (int) $request->quantity),
            ]
        );

        // Refresh agar quantity menjadi integer (bukan DB Expression)
        $cart->refresh();
        $cart->load('book.category');

        return $this->successResponse(
            new CartResource($cart),
            'Buku berhasil ditambahkan ke keranjang',
            201
        );
    }

    /** PATCH /api/cart/{id} — Mengubah kuantitas item */
    public function update(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Cek stok
        if ($cart->book->stock < $request->quantity) {
            return $this->errorResponse('Stok buku tidak mencukupi.', 422);
        }

        $cart->update(['quantity' => $request->quantity]);
        $cart->load('book.category');

        return $this->successResponse(
            new CartResource($cart),
            'Kuantitas berhasil diperbarui'
        );
    }

    /** DELETE /api/cart/{id} — Menghapus item dari keranjang */
    public function destroy(Request $request, int $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cart->delete();

        return $this->successResponse(null, 'Item berhasil dihapus dari keranjang');
    }
}
