@extends($theme.'.layouts.app')
@section('title', $event->title)

@section('content')
<x-event.event-detail :theme=$theme :event=$event />
@endsection