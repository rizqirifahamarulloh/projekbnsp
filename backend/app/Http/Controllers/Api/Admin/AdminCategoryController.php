<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;

/**
 * Controller Admin untuk CRUD Kategori
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AdminCategoryController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/categories */
    public function index()
    {
        $categories = Category::withCount('books')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            $categories,
            CategoryResource::collection($categories),
            'Daftar kategori berhasil diambil'
        );
    }

    /** POST /api/admin/categories */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);

        return $this->successResponse(
            new CategoryResource($category),
            'Kategori berhasil ditambahkan',
            201
        );
    }

    /** GET /api/admin/categories/{id} */
    public function show(int $id)
    {
        $category = Category::withCount('books')->findOrFail($id);

        return $this->successResponse(
            new CategoryResource($category),
            'Detail kategori berhasil diambil'
        );
    }

    /** PUT /api/admin/categories/{id} */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return $this->successResponse(
            new CategoryResource($category),
            'Kategori berhasil diperbarui'
        );
    }

    /** DELETE /api/admin/categories/{id} */
    public function destroy(int $id)
    {
        $category = Category::withCount('books')->findOrFail($id);

        // Cegah hapus kategori yang masih punya buku
        if ($category->books_count > 0) {
            return $this->errorResponse(
                "Kategori '{$category->name}' tidak dapat dihapus karena masih memiliki {$category->books_count} buku.",
                422
            );
        }

        $category->delete();

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }
}
