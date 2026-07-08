<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function respondWithError(string $message, int $statusCode)
    {
        return response()->json([
            'success' => false,
            'error' => [
                'http_code' => $statusCode,
                'message' => $message
            ]
        ], $statusCode);
    }

    // protected function respondSuccess(string $message, int $statusCode)
    // {
    //     return response()->json([
    //         'success' => false,
    //         'error' => [
    //             'http_code' => $statusCode,
    //             'message' => $message
    //         ]
    //     ], $statusCode);
    // }
}
