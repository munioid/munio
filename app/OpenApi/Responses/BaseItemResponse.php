<?php

namespace App\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BaseItemResponse'
)]
class BaseItemResponse
{
    #[OA\Property(example: true)]
    public bool $success;

    #[OA\Property(
        type: 'object'
    )]
    public object $data;
}
