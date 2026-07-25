@extends('default.layouts.app')

@section('header')
<x-header :organization="$organization" />
@endsection

@section('content')
<!-- POST HOME SLIDER -->
<x-blog.post-home-slider />
<!-- EVENT HOME SLIDER -->
<x-event.event-home-slider />
@endsection