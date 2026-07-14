<?php

namespace App\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BaseCollectionResponse'
)]
class BaseCollectionResponse
{
    #[OA\Property(example: true)]
    public bool $success;

    #[OA\Property(
        type: 'array',
        items: new OA\Items()
    )]
    public array $data;
}
