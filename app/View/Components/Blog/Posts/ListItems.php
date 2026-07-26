<?php

namespace App\View\Components\Blog\Posts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class ListItems extends Component
{
    public string $theme;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public LengthAwarePaginator $posts
    ) {
        $this->theme = 'default';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.blog.posts.list-items');
    }
}
