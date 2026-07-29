<div class="space-y-5 py-4">
    @foreach ($this->posts as $post)
    <x-blog.posts.list-card :post=$post />
    @endforeach

    @if ($this->posts->hasMorePages())
    <x-partial.load-more :theme=$theme />
    @endif
</div>