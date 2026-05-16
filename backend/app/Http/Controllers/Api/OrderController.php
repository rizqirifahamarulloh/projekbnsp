<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk pesanan pengguna
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class OrderController extends Controller
{
    use ApiResponse;

    /** GET /api/orders — Daftar pesanan user */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('orderItems.book')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            $orders,
            OrderResource::collection($orders),
            'Daftar pesanan berhasil diambil'
        );
    }

    /** POST /api/orders — Buat pesanan baru dari keranjang */
    public function store(Request $request)
    {
        $user = $request->user();

        // Ambil semua item di keranjang user
        $cartItems = Cart::with('book')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return $this->errorResponse('Keranjang kosong.', 422);
        }

        // Validasi stok sebelum checkout
        foreach ($cartItems as $item) {
            if ($item->book->stock < $item->quantity) {
                return $this->errorResponse(
                    "Stok buku '{$item->book->title}' tidak mencukupi. Tersisa: {$item->book->stock}.",
                    422
                );
            }
        }

        // Hitung total harga
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->book->price);

        // Buat pesanan dalam transaksi database
        $order = DB::transaction(function () use ($user, $cartItems, $totalPrice, $request) {
            $order = Order::create([
                'user_id'        => $user->id,
                'order_code'     => Order::generateOrderCode(),
                'total_price'    => $totalPrice,
                'status'         => 'pending',
                'payment_method' => $request->payment_method ?? 'midtrans',
            ]);

            // Buat order items dan kurangi stok
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id'  => $item->book_id,
                    'quantity' => $item->quantity,
                    'price'    => $item->book->price, // Snapshot harga saat checkout
                ]);

                // Kurangi stok buku
                $item->book->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang setelah checkout
            Cart::where('user_id', $user->id)->delete();

            return $order;
        });

        // Generate Midtrans token jika metode pembayaran = midtrans
        $midtransToken = null;
        if ($order->payment_method === 'midtrans') {
            $midtransToken = $this->generateMidtransToken($order);
            $order->update(['midtrans_token' => $midtransToken]);
        }

        $order->load('orderItems.book');

        return $this->successResponse(
            new OrderResource($order),
            'Pesanan berhasil dibuat',
            201
        );
    }

    /** GET /api/orders/{order_code} — Detail pesanan */
    public function show(Request $request, string $orderCode)
    {
        $order = Order::with('orderItems.book.category')
            ->where('user_id', $request->user()->id)
            ->where('order_code', $orderCode)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        return $this->successResponse(
            new OrderResource($order),
            'Detail pesanan berhasil diambil'
        );
    }

    /** POST /api/orders/notification — Webhook Midtrans */
    public function notification(Request $request)
    {
        $payload = $request->all();

        $orderId     = $payload['order_id'] ?? null;
        $statusCode  = $payload['status_code'] ?? null;
        $transaction = $payload['transaction_status'] ?? null;

        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        // Update status berdasarkan notifikasi Midtrans
        if ($transaction === 'capture' || $transaction === 'settlement') {
            $order->update(['status' => 'paid']);
        } elseif ($transaction === 'cancel' || $transaction === 'deny' || $transaction === 'expire') {
            $order->update(['status' => 'cancelled']);
            // Kembalikan stok saat pesanan dibatalkan
            foreach ($order->orderItems as $item) {
                $item->book->increment('stock', $item->quantity);
            }
        }

        return $this->successResponse(null, 'Notifikasi berhasil diproses');
    }

    /**
     * Generate token Midtrans Snap
     * Pada mode sandbox, kita simulasikan pembuatan token
     */
    private function generateMidtransToken(Order $order): ?string
    {
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));

        if (!$serverKey || $serverKey === 'SB-Mid-server-XXXXXXXXXXXXXX') {
            // Jika belum dikonfigurasi, return dummy token untuk development
            return 'SANDBOX-TOKEN-' . strtoupper(substr(md5($order->order_code), 0, 16));
        }

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = $serverKey;
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized  = env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds        = env('MIDTRANS_IS_3DS', true);

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            // Log error tapi tetap lanjutkan
            \Log::error('Midtrans error: ' . $e->getMessage());
            return null;
        }
    }
}
