@extends('default.layouts.app')
@section('title', $post->title)

@section('content')
<x-blog.posts.detail :post=$post />
@endsection