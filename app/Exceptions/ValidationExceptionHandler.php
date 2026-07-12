<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidationExceptionHandler extends Exception
{
    public function render(
        ValidationException $exception
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'http_code' => 422,
            'message' => collect($exception->errors())
                ->flatten()
                ->first()
        ], 422);
    }
}
