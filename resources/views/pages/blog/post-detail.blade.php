@extends('default.layouts.app')
@section('title', $post->title)

@section('content')
<livewire:blog.post-detail :post=$post />
@endsection