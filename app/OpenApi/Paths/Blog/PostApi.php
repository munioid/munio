<?php

namespace App\OpenApi\Paths\Blog;

use OpenApi\Attributes as OA;

class PostApi
{
    #[OA\Get(
        path: '/api/blog/posts',
        summary: 'List Posts',
        tags: ['Blog'],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(ref: '#/components/parameters/SearchParameter')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BasePaginationResponse'),
                new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PostResponse')
                        )
                    ]
                )
            ]
        )
    )]
    public function index(): void {}

    #[OA\Get(
        path: '/api/blog/posts/{id}',
        summary: 'Detail Post',
        tags: ['Blog'],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(ref: '#/components/parameters/IdParameter')]
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
                            ref: '#/components/schemas/PostResponse'
                        )
                    ]
                )
            ]
        )
    )]
    public function detail(): void {}
}
