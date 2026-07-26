@extends('default.layouts.app')
@section('title', 'Reservasi Acara')

@section('content')
<livewire:event.reservation :organization=$organization :event=$event />
@endsection