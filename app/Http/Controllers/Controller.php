<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Controller
{
    protected int $statusCode = 200;

    protected function respondWithError(string $message, int $statusCode)
    {
        $this->statusCode = $statusCode != 0 ? $statusCode : 500;
        $data = [
            'success' => false,
            'error' => [
                'http_code' => $this->statusCode,
                'message' => $message
            ]
        ];
        return $this->respondWithArray($data);
    }

    protected function respondWithItem(JsonResource $item, array $headers = []): JsonResponse
    {
        return $this->respondWithArray($item->resolve(), $headers);
    }

    protected function respondSuccess(string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    protected function respondWithArray(array $data, array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => $this->statusCode == 200 ? true : false,
            'data' => $data
        ], $this->statusCode, $headers);
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
