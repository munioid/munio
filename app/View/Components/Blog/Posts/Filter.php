<?php

namespace App\View\Components\Blog\Posts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Filter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $categories,
        public ?string $selectedCategory,
        public Collection $tags,
        public array $selectedTags
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('default.components.blog.posts.filter');
    }
}
