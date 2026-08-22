<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\Event\Event;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->published()
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get()
            ->map(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'published_at' => $post->published_at,
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                ] : null,
                'cover' => $post->cover ? [
                    'path' => $post->cover->getPath(),
                ] : null,
            ]);

        $events = Event::query()
            ->published()
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get()
            ->map(fn ($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'event_date' => $event->event_date,
                'price' => $event->price,
                'register_url' => $event->external_link,
                'cover' => $event->cover ? [
                    'path' => $event->cover->getPath(),
                ] : null,
            ]);

        return Inertia::render('Home', [
            'posts' => $posts,
            'events' => $events,
        ]);
    }
}
