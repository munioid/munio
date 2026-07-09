<?php

namespace App\OpenApi\Responses\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginResponse'
)]
class LoginResponse
{
    #[OA\Property(
        example: true
    )]
    public bool $success;

    #[OA\Property(
        type: 'string'
    )]
    public string $token;

    #[OA\Property(
        property: 'token_type',
        type: 'string'
    )]
    public string $tokenType;
}
