<?php

namespace App\OpenApi\Responses\Global;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CategoryResponse'
)]
class CategoryResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        type: 'string',
        example: 'Aperiam'
    )]
    public string $name;

    #[OA\Property(
        type: 'string',
        example: 'aperiam'
    )]
    public string $slug;

    #[OA\Property(
        type: 'string',
        example: 'Molestiae consequatur dolorem sed quae autem.'
    )]
    public string $description;
}
