<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Controller
{
    protected int $statusCode = 200;

    protected function respondWithError(string $message, int $statusCode)
    {
        $this->statusCode = $statusCode != 0 ? $statusCode : 500;
        return response()->json([
            'success' => false,
            'http_code' => $this->statusCode,
            'message' => $message
        ], $this->statusCode);
    }

    protected function respondWithPagination(LengthAwarePaginator $paginated, string $resourceClass, array $additionalMeta = []): JsonResponse
    {
        return $this->respondWithArray(
            data: $resourceClass::collection($paginated->items())->resolve(),
            meta: array_merge([
                'pagination' => [
                    'total' => $paginated->total(),
                    'count' => $paginated->count(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'total_pages' => $paginated->lastPage()
                ]
            ], $additionalMeta)
        );
    }

    protected function respondWithCollection(Collection $data, string $resourceClass): JsonResponse
    {
        return $this->respondWithArray(
            data: $resourceClass::collection($data)->resolve(),
        );
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

    protected function respondWithArray(array $data, array $meta = [],  array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => $this->statusCode == 200 ? true : false,
            'data' => $data,
            ...(! empty($meta) ? ['meta' => $meta] : []),
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
