<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller Admin CRUD Buku (web)
 * [BNSP: Membuat Antarmuka Pengguna]
 */
class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'publisher'    => 'required|string|max:255',
            'year'         => 'required|integer|min:1900|max:' . date('Y'),
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'  => 'required|string',
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'publisher'    => 'required|string|max:255',
            'year'         => 'required|integer|min:1900|max:' . date('Y'),
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'  => 'required|string',
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }
}
