<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidationExceptionHandler extends Exception
{
    public function render(
        Request $request,
        ValidationException $exception
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $exception->errors(),
        ], 422);
    }
}
