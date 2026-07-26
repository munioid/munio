@extends($theme.'.layouts.auth')

@section('title', 'Masuk')

@section('content')
<livewire:authentication.login :theme=$theme :organization=$organization />
@endsection