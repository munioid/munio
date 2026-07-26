@extends('default.layouts.app')
@section('title', 'Acara')

@section('content')
<livewire:event.events :categories=$categories />
@endsection