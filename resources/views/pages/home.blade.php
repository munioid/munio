@extends($theme.'.layouts.app')
@section('title', 'Home')

@section('content')
<!-- POST HOME SLIDER -->
<livewire:home.post-component />
<!-- EVENT HOME SLIDER -->
<livewire:home.event-component />
@endsection