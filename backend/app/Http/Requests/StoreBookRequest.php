<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'publisher'    => 'required|string|max:255',
            'year'         => 'required|integer|min:1900|max:' . date('Y'),
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'  => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak ditemukan.',
            'title.required'       => 'Judul buku wajib diisi.',
            'author.required'      => 'Nama penulis wajib diisi.',
            'publisher.required'   => 'Nama penerbit wajib diisi.',
            'year.required'        => 'Tahun terbit wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            'stock.required'       => 'Stok wajib diisi.',
            'cover_image.image'    => 'Cover harus berupa gambar.',
            'cover_image.max'      => 'Ukuran cover maksimal 2MB.',
            'description.required' => 'Deskripsi wajib diisi.',
        ];
    }
}
