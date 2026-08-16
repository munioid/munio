<?php

namespace App\OpenApi\Paths\Event;

use OpenApi\Attributes as OA;

class ReservationApi
{
    #[OA\Post(
        path: '/api/events/reservations',
        summary: 'Create Reservation',
        tags: ['Event'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: '#/components/schemas/ReservationRequest'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            allOf: [
                new OA\Schema(ref: '#/components/schemas/BaseSuccessResponse'),
                new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Reservation submitted.'
                        ),
                    ],
                ),
            ]
        )
    )]
    public function store(): void {}
}
