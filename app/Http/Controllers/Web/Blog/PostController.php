<?php

namespace App\Http\Controllers\Web\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $organization = Filament::getTenant();

        // Get filter parameters
        $categoryId = $request->query('category');
        $tagId = $request->query('tag');
        $search = $request->query('search');

        // Base query for published posts
        $query = Post::query()
            ->published()
            ->orderByDesc('published_at');

        // Apply filters
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($tagId) {
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('blog_tags.id', $tagId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Paginate results with relationships and cover attachment
        $posts = $query
            ->with(['category', 'tags', 'cover'])
            ->paginate(12)
            ->withQueryString();

        // Get all categories and tags for filter
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return Inertia::render('Blog/PostsList', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'filters' => [
                'category' => $categoryId,
                'tag' => $tagId,
                'search' => $search,
            ],
        ]);
    }

    public function loadMore(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->query('category');
        $tagId = $request->query('tag');
        $search = $request->query('search');

        // Base query for published posts
        $query = Post::query()
            ->published()
            ->orderByDesc('published_at');

        // Apply filters
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($tagId) {
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('blog_tags.id', $tagId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Paginate results with relationships and cover attachment
        $posts = $query
            ->with(['category', 'tags', 'cover'])
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'data' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'total' => $posts->total(),
        ]);
    }

    public function detail(string $slug)
    {
        $organization = Filament::getTenant();
        $post = Post::query()
            ->whereSlug($slug)
            ->with(['category', 'tags', 'cover'])
            ->first();

        if (! $post) {
            abort(404, 'Not found.');
        }

        // Get related posts (same category or tags)
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                      ->orWhereHas('tags', function ($q) use ($post) {
                          $q->whereIn('blog_tags.id', $post->tags->pluck('id'));
                      });
            })
            ->with(['category', 'tags', 'cover'])
            ->limit(3)
            ->get();

        return Inertia::render('Blog/PostDetail', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
