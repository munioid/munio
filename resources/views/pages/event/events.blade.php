@extends($theme.'.layouts.app')
@section('title', 'Acara')

@section('content')
<livewire:event.events :theme=$theme :categories=$categories />
@endsection