@extends($theme.'.layouts.auth')

@section('title', 'Reservasi Acara')

@section('content')

<livewire:authentication.my-reservation :theme=$theme :organization=$organization />

@endsection