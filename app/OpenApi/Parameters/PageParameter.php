<?php

namespace App\OpenApi\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'PageParameter',
    name: 'page',
    in: 'query',
    description: 'Page of pagination',
    required: false,
    schema: new OA\Schema(
        type: 'integer'
    )
)]
class PageParameter {}
