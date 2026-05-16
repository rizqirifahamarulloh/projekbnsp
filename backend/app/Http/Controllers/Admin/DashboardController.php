<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Contact;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Controller Dashboard Admin — menampilkan statistik & ringkasan lengkap
 * [BNSP: Membuat Antarmuka Pengguna]
 */
class DashboardController extends Controller
{
    public function index()
    {
        // ═══════════════════════════════════════════
        // BAGIAN 1: KARTU RINGKASAN (Summary Cards)
        // ═══════════════════════════════════════════

        $totalBuku      = Book::count();
        $totalPengguna  = User::where('role', 'user')->count();
        $totalPesanan   = Order::count();

        // Pendapatan bulan ini — hanya pesanan berstatus 'paid'
        $pendapatanBulanIni = Order::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Buku dengan stok menipis (≤ 5)
        $stokMenipis = Book::where('stock', '<=', 5)->count();

        // Pesan kontak yang belum dibaca
        $pesanBelumDibaca = Contact::where('is_read', false)->count();

        // ═══════════════════════════════════════════
        // BAGIAN 2: DATA GRAFIK
        // ═══════════════════════════════════════════

        // --- Grafik 1: Pesanan per hari (7 hari terakhir) ---
        // Buat array 7 hari terakhir, isi 0 untuk hari tanpa pesanan
        $mulai = Carbon::now()->subDays(6)->startOfDay();
        $akhir = Carbon::now()->endOfDay();

        // Query jumlah pesanan per tanggal
        $pesananPerHari = Order::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereBetween('created_at', [$mulai, $akhir])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('jumlah', 'tanggal');

        // Isi 0 untuk hari yang tidak ada pesanan
        $grafikPesananMingguan = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $label   = Carbon::now()->subDays($i)->translatedFormat('D, d M'); // Sen, 13 Mei
            $grafikPesananMingguan->push([
                'tanggal' => $tanggal,
                'label'   => $label,
                'jumlah'  => $pesananPerHari[$tanggal] ?? 0,
            ]);
        }

        // --- Grafik 2: Pendapatan per Kategori ---
        // JOIN order_items → books → categories, hanya pesanan paid
        $grafikPendapatanKategori = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->where('orders.status', 'paid')
            ->select(
                'categories.name as kategori',
                DB::raw('SUM(order_items.price * order_items.quantity) as total_pendapatan')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total_pendapatan')
            ->get();

        // --- Grafik 3: Jumlah pesanan per status ---
        $grafikStatusPesanan = Order::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        // Pastikan semua status ada (default 0)
        $grafikStatusPesanan = collect([
            'pending'   => $grafikStatusPesanan['pending'] ?? 0,
            'paid'      => $grafikStatusPesanan['paid'] ?? 0,
            'cancelled' => $grafikStatusPesanan['cancelled'] ?? 0,
        ]);

        // ═══════════════════════════════════════════
        // BAGIAN 3: DATA TABEL
        // ═══════════════════════════════════════════

        // 8 pesanan terbaru
        $pesananTerbaru = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 5 buku stok menipis (≤ 10)
        $bukuStokMenipis = Book::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // 5 pesan terbaru
        $pesanTerbaru = Contact::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Waktu refresh terakhir
        $waktuRefresh = Carbon::now()->translatedFormat('l, d F Y — H:i:s');

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalPengguna',
            'totalPesanan',
            'pendapatanBulanIni',
            'stokMenipis',
            'pesanBelumDibaca',
            'grafikPesananMingguan',
            'grafikPendapatanKategori',
            'grafikStatusPesanan',
            'pesananTerbaru',
            'bukuStokMenipis',
            'pesanTerbaru',
            'waktuRefresh',
        ));
    }
}
