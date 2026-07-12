<?php

namespace App\OpenApi\Responses\File;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FileResponse'
)]
class FileResponse
{
    #[OA\Property(
        format: 'uuid'
    )]
    public string $id;

    #[OA\Property(
        property: 'file_name',
        type: 'string'
    )]
    public string $fileName;

    #[OA\Property(
        property: 'file_path',
        type: 'string'
    )]
    public string $filePath;
}