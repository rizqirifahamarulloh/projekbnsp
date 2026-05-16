<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Controller Admin Pesanan (web)
 * [BNSP: Membuat Antarmuka Pengguna]
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'orderItems.book'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,paid,cancelled']);

        $order = Order::with('orderItems.book')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->book->increment('stock', $item->quantity);
            }
        }

        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->book->decrement('stock', $item->quantity);
            }
        }

        $order->update(['status' => $newStatus]);

        return redirect()->route('admin.orders.index')
            ->with('success', "Status pesanan berhasil diubah menjadi '{$newStatus}'!");
    }
}
