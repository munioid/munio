<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Munio'
)]
#[OA\SecurityScheme(
    type: 'http',
    description: 'Login dengan bearer token',
    name: 'bearerToken',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    securityScheme: 'bearerToken'
)]
class OpenApi {}
