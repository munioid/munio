<?php

namespace App\OpenApi\Paths\Authentication;

use OpenApi\Attributes as OA;

class AuthDocs
{
    #[OA\Post(
        path: "/api/auth/login",
        summary: "User Login",
        tags: ["Authentication"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: '#/components/schemas/LoginRequest'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            ref: '#/components/schemas/LoginResponse'
        )
    )]
    public function login(): void {}

    #[OA\Post(
        path: "/api/auth/logout",
        summary: "Logout",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BaseSuccessResponse'),
                new OA\Schema(ref: '#/components/schemas/LogoutResponse')
            ]
        )
    )]
    public function logout(): void {}
}
