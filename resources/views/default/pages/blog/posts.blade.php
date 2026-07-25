@extends('default.layouts.app')

@section('title', 'Berita')

@section('header')
<x-header :organization="$organization" />
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 pb-3">
    <livewire:blog.post-list :categories=$categories :tags=$tags />
</div>
@endsection