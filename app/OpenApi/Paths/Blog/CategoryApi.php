<?php

namespace App\OpenApi\Paths\Blog;

use OpenApi\Attributes as OA;

class CategoryApi
{
   #[OA\Get(
        path: '/api/blog/categories',
        summary: 'List Categories',
        tags: ['Blog'],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(ref: '#/components/parameters/SearchParameter')]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BaseCollectionResponse'),
                new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/CategoryResponse')
                        )
                    ]
                )
            ]
        )
    )]
    public function index(): void {}
}
