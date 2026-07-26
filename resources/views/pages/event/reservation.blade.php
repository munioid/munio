@extends($theme.'.layouts.app')
@section('title', 'Reservasi Acara')

@section('content')
<livewire:event.reservation :theme=$theme :organization=$organization :event=$event />
@endsection