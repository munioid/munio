<?php

namespace App\OpenApi\Requests\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginRequest',
    required: ['email', 'password']
)]
class LoginRequest
{
    #[OA\Property(
        type: 'string',
        example: 'admin@example.com'
    )]
    public string $email;

    #[OA\Property(
        type: 'string',
        example: 'admin123'
    )]
    public string $password;
}
