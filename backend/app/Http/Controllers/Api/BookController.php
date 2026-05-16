<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Controller untuk endpoint publik buku (tanpa auth)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class BookController extends Controller
{
    use ApiResponse;

    /** GET /api/books — Daftar buku dengan filter, search, sort, dan pagination */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Filter berdasarkan kategori (slug)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Pencarian berdasarkan judul atau penulis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                // Sortir berdasarkan jumlah order item (terlaris)
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $books = $query->paginate(15);

        return $this->paginatedResponse(
            $books,
            BookResource::collection($books),
            'Daftar buku berhasil diambil'
        );
    }

    /** GET /api/books/{slug} — Detail buku berdasarkan slug */
    public function show(string $slug)
    {
        $book = Book::with('category')->where('slug', $slug)->first();

        if (!$book) {
            return $this->errorResponse('Buku tidak ditemukan.', 404);
        }

        return $this->successResponse(
            new BookResource($book),
            'Detail buku berhasil diambil'
        );
    }
}
