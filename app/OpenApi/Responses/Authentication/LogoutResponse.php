<?php

namespace App\OpenApi\Responses\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LogoutResponse'
)]
class LogoutResponse
{
    #[OA\Property(
        type: 'string',
        example: 'Logout berhasil.'
    )]
    public string $message;
}
