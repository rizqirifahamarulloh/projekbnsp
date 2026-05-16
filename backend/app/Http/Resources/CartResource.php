<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'book'     => new BookResource($this->whenLoaded('book')),
            'quantity' => $this->quantity,
            'subtotal' => $this->when($this->book, fn() => $this->quantity * $this->book->price),
        ];
    }
}
