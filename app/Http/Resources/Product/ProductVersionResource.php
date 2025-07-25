<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'version_number' => $this->version_number,
            'title' => $this->title,
            'content' => $this->content,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'category_id' => $this->category_id,
            'changes' => json_decode($this->changes, true),
            'old_attributes' => json_decode($this->old_attributes, true),
            'new_attributes' => json_decode($this->new_attributes, true),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
