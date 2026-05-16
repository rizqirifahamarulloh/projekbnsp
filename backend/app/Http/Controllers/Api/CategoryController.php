<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;

/**
 * Controller untuk endpoint publik kategori (tanpa auth)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class CategoryController extends Controller
{
    use ApiResponse;

    /** GET /api/categories — Daftar semua kategori */
    public function index()
    {
        $categories = Category::withCount('books')->get();

        return $this->successResponse(
            CategoryResource::collection($categories),
            'Daftar kategori berhasil diambil'
        );
    }

    /** GET /api/categories/{slug}/books — Buku berdasarkan kategori */
    public function books(string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan.', 404);
        }

        $books = $category->books()->with('category')->paginate(15);

        return $this->paginatedResponse(
            $books,
            BookResource::collection($books),
            "Daftar buku kategori '{$category->name}'"
        );
    }
}
