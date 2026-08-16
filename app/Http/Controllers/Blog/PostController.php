<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\PostIndexRequest;
use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Post;
use Exception;
use Throwable;

class PostController extends Controller
{
    public function index(PostIndexRequest $request)
    {
        $search = $request->search;

        $posts = Post::query()
            ->with(['category', 'tags', 'covers'])
            ->when($search, function ($query, $searchKey) {
                $query->search($searchKey);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return $this->respondWithPagination($posts, PostResource::class);
    }

    public function detail(string $id)
    {
        try {
            $post = Post::query()
                ->with(['category', 'tags', 'covers'])
                ->find($id);

            if (! $post) {
                throw new Exception('Post tidak ditemukan.', 404);
            }

            return $this->respondWithItem(PostResource::make($post));
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}
