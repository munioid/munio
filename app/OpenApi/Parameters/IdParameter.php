<?php

namespace App\OpenApi\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'IdParameter',
    name: 'id',
    in: 'path',
    required: true,
    schema: new OA\Schema(
        format: 'uuid'
    )
)]
class IdParameter {}
