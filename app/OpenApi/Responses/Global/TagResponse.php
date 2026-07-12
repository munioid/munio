<?php

namespace App\OpenApi\Responses\Global;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TagResponse'
)]
class TagResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        type: 'string',
        example: 'Ipsam'
    )]
    public string $name;

    #[OA\Property(
        type: 'string',
        example: 'ipsam'
    )]
    public string $slug;

    #[OA\Property(
        type: 'string',
        example: 'Rerum quis architecto sit itaque.'
    )]
    public string $description;
}