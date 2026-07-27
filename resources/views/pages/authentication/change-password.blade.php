@extends($theme.'.layouts.auth')

@section('title', 'Ubah Password')

@section('content')

<livewire:authentication.change-password :theme=$theme :organization=$organization />

@endsection