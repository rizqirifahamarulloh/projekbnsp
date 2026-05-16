<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id'  => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku wajib dipilih.',
            'book_id.exists'   => 'Buku tidak ditemukan.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min'      => 'Jumlah minimal 1.',
        ];
    }
}
