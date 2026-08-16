<?php

namespace App\OpenApi\Requests\Event;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ReservationRequest',
    required: ['event_id', 'name', 'email', 'quantity']
)]
class ReservationRequest
{
    #[OA\Property(
        property: 'event_id',
        format: 'uuid'
    )]
    public string $eventId;

    #[OA\Property(
        property: 'package_id',
        format: 'uuid'
    )]
    public string $packageId;

    #[OA\Property(
        type: 'string',
        example: 'Test'
    )]
    public string $name;

    #[OA\Property(
        type: 'string',
        example: 'test@example.com'
    )]
    public string $email;

    #[OA\Property(
        type: 'integer',
        example: 1
    )]
    public int $quantity;
}
