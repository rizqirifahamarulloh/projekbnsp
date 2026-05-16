<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'  => 'sometimes|required|exists:categories,id',
            'title'        => 'sometimes|required|string|max:255',
            'author'       => 'sometimes|required|string|max:255',
            'publisher'    => 'sometimes|required|string|max:255',
            'year'         => 'sometimes|required|integer|min:1900|max:' . date('Y'),
            'price'        => 'sometimes|required|integer|min:0',
            'stock'        => 'sometimes|required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'  => 'sometimes|required|string',
        ];
    }
}
