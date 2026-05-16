<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller Admin untuk CRUD Buku
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AdminBookController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/books */
    public function index()
    {
        $books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            $books,
            BookResource::collection($books),
            'Daftar buku berhasil diambil'
        );
    }

    /** POST /api/admin/books */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);

        // Upload cover image jika ada
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $book = Book::create($data);
        $book->load('category');

        return $this->successResponse(
            new BookResource($book),
            'Buku berhasil ditambahkan',
            201
        );
    }

    /** GET /api/admin/books/{id} */
    public function show(int $id)
    {
        $book = Book::with('category')->findOrFail($id);

        return $this->successResponse(
            new BookResource($book),
            'Detail buku berhasil diambil'
        );
    }

    /** PUT /api/admin/books/{id} */
    public function update(UpdateBookRequest $request, int $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->validated();

        // Update slug jika judul berubah
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Upload cover baru jika ada, hapus yang lama
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $book->update($data);
        $book->load('category');

        return $this->successResponse(
            new BookResource($book),
            'Buku berhasil diperbarui'
        );
    }

    /** DELETE /api/admin/books/{id} */
    public function destroy(int $id)
    {
        $book = Book::findOrFail($id);

        // Hapus cover image dari storage
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return $this->successResponse(null, 'Buku berhasil dihapus');
    }
}
