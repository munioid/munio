<?php

namespace App\View\Components\Blog\Posts;

use App\Models\Blog\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Detail extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Post $post
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('default.components.blog.posts.detail');
    }
}
