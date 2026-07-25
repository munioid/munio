@extends('default.layouts.app')

@section('title', 'Berita')

@section('content')
<div class="min-h-screen bg-gray-50 pb-3">
    <livewire:blog.posts :categories=$categories :tags=$tags />
</div>
@endsection