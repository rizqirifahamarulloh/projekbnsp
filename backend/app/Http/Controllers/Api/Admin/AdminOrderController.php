<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Controller Admin untuk manajemen pesanan
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AdminOrderController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/orders — Daftar semua pesanan (bisa difilter by status) */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.book'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return $this->paginatedResponse(
            $orders,
            OrderResource::collection($orders),
            'Daftar pesanan berhasil diambil'
        );
    }

    /** GET /api/admin/orders/{id} — Detail pesanan */
    public function show(int $id)
    {
        $order = Order::with(['user', 'orderItems.book.category'])->findOrFail($id);

        return $this->successResponse(
            new OrderResource($order),
            'Detail pesanan berhasil diambil'
        );
    }

    /** PATCH /api/admin/orders/{id}/status — Ubah status pesanan */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $order = Order::with('orderItems.book')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Jika pesanan dibatalkan, kembalikan stok
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->book->increment('stock', $item->quantity);
            }
        }

        // Jika pesanan yang sebelumnya dibatalkan di-reaktivasi, kurangi stok kembali
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->book->decrement('stock', $item->quantity);
            }
        }

        $order->update(['status' => $newStatus]);

        return $this->successResponse(
            new OrderResource($order),
            "Status pesanan berhasil diubah menjadi '{$newStatus}'"
        );
    }
}
