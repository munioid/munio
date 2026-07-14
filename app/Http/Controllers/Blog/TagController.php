<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Global\TagResource;
use App\Models\Blog\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->search;
        
        $tags = Tag::query()
            ->when($search, function ($query, $searchKey) {
                $query->search($searchKey);
            })
            ->paginate(10);

        return $this->respondWithPagination($tags, TagResource::class);
    }
}
