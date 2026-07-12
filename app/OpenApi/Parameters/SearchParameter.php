<?php

namespace App\OpenApi\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'SearchParameter',
    name: 'search',
    in: 'query',
    description: 'Search keyword',
    required: false,
    schema: new OA\Schema(
        type: 'string'
    )
)]
class SearchParameter {}
