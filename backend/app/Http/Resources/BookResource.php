<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'title'       => $this->title,
            'slug'        => $this->slug,
            'author'      => $this->author,
            'publisher'   => $this->publisher,
            'year'        => $this->year,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'description' => $this->description,
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
