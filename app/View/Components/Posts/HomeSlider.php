<?php

namespace App\View\Components\Posts;

use App\Models\Blog\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class HomeSlider extends Component
{
    public Collection $posts;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->posts = Post::query()
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.posts.home-slider', [$this->posts]);
    }
}
