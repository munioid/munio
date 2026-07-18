<?php

namespace App\Http\Resources\Event;

use App\Enums\PricingTypeEnum;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Global\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'start_at' => $this->start_at?->toDateString(),
            'end_at' => $this->end_at?->toDateString(),
            'cover' => new FileResource($this->whenLoaded('cover')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'published' => $this->published,
            'published_at' => $this->published_at?->toDateTimestring(),
            'pricing_type' => $this->pricing_type,

            $this->mergeWhen($this->pricing_type == PricingTypeEnum::SINGLE, [
                'price' => $this->price,
                'stocks' => $this->stocks,
            ]),

            $this->mergeWhen($this->pricing_type == PricingTypeEnum::PACKAGE, [
                'packages' => EventPackageResource::collection($this->packages)
            ]),

            $this->mergeWhen($this->pricing_type == PricingTypeEnum::EXTERNAL, [
                'price' => $this->price,
                'external_link' => $this->external_link
            ]),
        ];
    }
}
