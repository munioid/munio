<?php

namespace App\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BasePaginationResponse'
)]
class BasePaginationResponse
{
    #[OA\Property(example: true)]
    public bool $success;

    #[OA\Property(
        type: 'array',
        items: new OA\Items
    )]
    public array $data;

    #[OA\Property(
        type: 'object',
        properties: [
            new OA\Property(
                property: 'pagination',
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'total',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'count',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'per_page',
                        type: 'integer',
                        example: 10
                    ),
                    new OA\Property(
                        property: 'current_page',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'total_pages',
                        type: 'integer',
                        example: 1
                    ),
                ]
            ),
        ]
    )]
    public object $meta;
}
