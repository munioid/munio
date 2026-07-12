<?php

namespace App\OpenApi\Responses\Blog;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PostResponse'
)]
class PostResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        type: 'string',
        example: 'Voluptatum sint quas dicta qui qui ad'
    )]
    public string $title;

    #[OA\Property(
        type: 'string',
        example: 'voluptatum-sint-quas-dicta-qui-qui-ad'
    )]
    public string $slug;

    #[OA\Property(
        type: 'string',
        example: 'Deserunt assumenda vero occaecati quia nam rerum iure est'
    )]
    public string $excerpt;

    #[OA\Property(
        type: 'string',
        example: '<p>Qui qui sed libero a et. Labore impedit consequuntur voluptates autem. Corrupti doloremque dolorem nostrum ut.</p>'
    )]
    public string $content;

    #[OA\Property(
        format: 'uri',
        example: 'http://www.ruecker.biz/facere-eaque-delectus-eum-et-sequi'
    )]
    public string $source;

    #[OA\Property(
        type: 'object',
        ref: '#/components/schemas/CategoryResponse'
    )]
    public object $category;

    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/TagResponse')
    )]
    public array $tags;

    #[OA\Property(
        property: 'published_by',
        type: 'string',
        example: 'Admin'
    )]
    public string $publishedBy;

    #[OA\Property(
        property: 'published_at',
        example: '2026-06-11 10:24:15'
    )]
    public string $publishedAt;

    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/FileResponse')
    )]
    public array $covers;

    #[OA\Property(
        property: 'created_at',
        example: '2026-06-11 10:24:15'
    )]
    public string $createdAt;

    #[OA\Property(
        property: 'updated_at',
        example: '2026-06-11 10:24:15'
    )]
    public string $updatedAt;
}
