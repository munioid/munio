<?php

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public Collection $posts;
    
    public function mount()
    {
        $this->posts = Post::query()
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }
};
?>

<div>
    <x-home.post-component :posts=$posts />
</div>