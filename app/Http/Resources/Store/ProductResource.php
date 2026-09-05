<?php

namespace App\Http\Resources\Store;

use App\Http\Resources\Global\CategoryResource;
use App\Http\Resources\Global\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (int) $this->price,
            'currency' => 'IDR',
            'cover_url' => $this->cover_url,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_status?->value,
            'weight' => (float) $this->weight,
            'is_active' => $this->is_active,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
