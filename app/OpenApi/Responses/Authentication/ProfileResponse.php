<?php

namespace App\OpenApi\Responses\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProfileResponse'
)]
class ProfileResponse
{
    #[OA\Property(
        property: 'id',
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        example: 'User Name'
    )]
    public string $name;

    #[OA\Property(
        property: 'email',
        format: 'email'
    )]
    public string $email;
}
