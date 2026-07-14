<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Global\CategoryResource;
use App\Models\Blog\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->search;
        
        $categories = Category::query()
            ->with(['parent'])
            ->when($search, function ($query, $searchKey) {
                $query->search($searchKey);
            })
            ->get();

        return $this->respondWithCollection($categories, CategoryResource::class);
    }
}
