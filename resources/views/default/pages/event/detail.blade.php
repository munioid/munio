@extends('default.layouts.app')
@section('title', $event->title)

@section('content')
<livewire:event.detail :event=$event />
@endsection