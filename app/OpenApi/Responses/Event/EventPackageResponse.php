<?php

namespace App\OpenApi\Responses\Event;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EventPackageResponse'
)]
class EventPackageResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        example: 'Package 1'
    )]
    public string $name;

    #[OA\Property(
        example: 'P1'
    )]
    public string $code;

    #[OA\Property(
        type: 'decimal',
        example: 100000
    )]
    public float $price;

    #[OA\Property(
        type: 'integer',
        example: 100
    )]
    public float $stocks;
}
