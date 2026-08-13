<div class="min-h-screen bg-gray-50 pb-4">
    <x-event.event-filter :theme=$theme :categories=$categories :selectedCategory=$selectedCategory />
    <x-event.event-list :theme=$theme :events=$events />
</div>