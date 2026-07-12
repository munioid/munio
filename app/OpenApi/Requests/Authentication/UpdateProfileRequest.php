<?php

namespace App\OpenApi\Requests\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateProfileRequest'
)]
class UpdateProfileRequest
{
    #[OA\Property(
        type: 'string',
        example: 'User Name'
    )]
    public string $name;

    #[OA\Property(
        type: 'string',
        format: 'email'
    )]
    public string $email;
}