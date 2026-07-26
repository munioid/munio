@extends($theme.'.layouts.app')
@section('title', $event->title)

@section('content')
<livewire:event.detail :theme=$theme :event=$event />
@endsection