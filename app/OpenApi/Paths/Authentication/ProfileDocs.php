<?php

namespace App\OpenApi\Paths\Authentication;

use OpenApi\Attributes as OA;

class ProfileDocs
{
    #[OA\Get(
        path: '/api/profile',
        summary: 'Get Profile',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BaseItemResponse'),
                new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/ProfileResponse'
                        ),
                    ]
                ),
            ]
        )
    )]
    public function profile(): void {}

    #[OA\Post(
        path: '/api/profile/update',
        summary: 'Update Profile',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: '#/components/schemas/UpdateProfileRequest'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BaseSuccessResponse'),
                new OA\Schema(ref: '#/components/schemas/UpdateProfileResponse'),
            ]
        )
    )]
    public function updateProfile(): void {}
}
