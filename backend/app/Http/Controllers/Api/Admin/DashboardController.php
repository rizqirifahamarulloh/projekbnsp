<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

/**
 * Controller Dashboard Admin — statistik dan ringkasan
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class DashboardController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/dashboard */
    public function index()
    {
        // Kartu ringkasan
        $totalBooks  = Book::count();
        $totalUsers  = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $revenue     = Order::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Buku terlaris (top 5)
        $bestSellers = Book::select('books.*')
            ->join('order_items', 'books.id', '=', 'order_items.book_id')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('books.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Pesanan 7 hari terakhir (untuk grafik line chart)
        $ordersChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pendapatan per kategori (untuk grafik doughnut)
        $revenueByCategory = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->where('orders.status', 'paid')
            ->select('categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('categories.name')
            ->get();

        // 5 pesanan terbaru
        $latestOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $this->successResponse([
            'summary' => [
                'total_books'     => $totalBooks,
                'total_users'     => $totalUsers,
                'total_orders'    => $totalOrders,
                'monthly_revenue' => $revenue,
            ],
            'best_sellers'        => $bestSellers,
            'orders_chart'        => $ordersChart,
            'revenue_by_category' => $revenueByCategory,
            'latest_orders'       => $latestOrders,
        ], 'Dashboard data berhasil diambil');
    }
}
