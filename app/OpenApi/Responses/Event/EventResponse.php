<?php

namespace App\OpenApi\Responses\Event;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EventResponse'
)]
class EventResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        type: 'string',
        example: 'Qui numquam inventore sunt aut sunt assumenda'
    )]
    public string $title;

    #[OA\Property(
        type: 'string',
        example: 'qui-numquam-inventore-sunt-aut-sunt-assumenda'
    )]
    public string $slug;

    #[OA\Property(
        type: 'string',
        example: 'Quasi asperiores officia ducimus rerum'
    )]
    public string $excerpt;

    #[OA\Property(
        type: 'string',
        example: '<p>Ipsa nihil repellat cupiditate eligendi.</p>'
    )]
    public string $content;

    #[OA\Property(
        property: 'start_at',
        format: 'date'
    )]
    public string $startAt;

    #[OA\Property(
        property: 'end_at',
        format: 'date'
    )]
    public string $endAt;

    #[OA\Property(
        type: 'object',
        ref: '#/components/schemas/FileResponse'
    )]
    public object $cover;

    #[OA\Property(
        type: 'object',
        ref: '#/components/schemas/CategoryResponse'
    )]
    public object $category;

    #[OA\Property(
        type: 'boolean'
    )]
    public bool $published;

    #[OA\Property(
        property: 'published_at',
        format: 'datetime',
        example: '2026-06-12 10:24:15'
    )]
    public string $publishedAt;

    #[OA\Property(
        property: 'pricing_type',
        example: 'package'
    )]
    public string $pricingType;

    #[OA\Property(
        type: 'decimal',
        example: 100000
    )]
    public float $price;

    #[OA\Property(
        type: 'integer',
        example: 100
    )]
    public float $stocks;

    #[OA\Property(
        property: 'external_url',
        example: 'http://www.ruecker.biz/facere-eaque-delectus-eum-et-sequi'
    )]
    public string $extenalUrl;

    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/EventPackageResponse')
    )]
    public array $packages;
}
