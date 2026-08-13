<?php

namespace App\View\Components\Blog\Posts;

use App\Models\Blog\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Detail extends Component
{
    public string $theme;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Post $post
    ) {
        $this->theme = 'default';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.blog.posts.detail');
    }
}
