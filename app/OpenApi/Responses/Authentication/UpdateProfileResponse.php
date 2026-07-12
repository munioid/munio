<?php

namespace App\OpenApi\Responses\Authentication;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateProfileResponse'
)]
class UpdateProfileResponse
{
    #[OA\Property(
        type: 'string',
        example: 'Profile berhasil dirubah.'
    )]
    public string $message;
}
