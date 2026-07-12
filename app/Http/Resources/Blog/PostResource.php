<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\Global\CategoryResource;
use App\Http\Resources\Global\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'source' => $this->source,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'published_by' => $this->publishedBy?->name,
            'published_at' => $this->published_at,
            'covers' => FileResource::collection($this->whenLoaded('covers')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
