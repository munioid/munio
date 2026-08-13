<?php

use App\Models\Blog\Post;
use Livewire\Component;

new class extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }
};
?>

<div>
    <x-blog.posts.detail :post=$post />
</div>