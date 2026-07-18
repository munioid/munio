<?php

namespace App\OpenApi\Paths\Event;

use OpenApi\Attributes as OA;

class EventApi
{
    #[OA\Get(
        path: '/api/events',
        summary: 'List Event',
        tags: ['Event'],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(ref: '#/components/parameters/SearchParameter')]
    #[OA\Parameter(ref: '#/components/parameters/PageParameter')]
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
                            items: new OA\Items(ref: '#/components/schemas/EventResponse')
                        )
                    ]
                )
            ]
        )
    )]
    public function index(): void {}

    #[OA\Get(
        path: '/api/events/{id}',
        summary: 'Detail Event',
        tags: ['Event'],
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
                            ref: '#/components/schemas/EventResponse'
                        )
                    ]
                )
            ]
        )
    )]
    public function detail(): void {}
}
