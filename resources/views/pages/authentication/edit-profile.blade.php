@extends($theme.'.layouts.auth')
@section('title', 'Ubah Profil')

@section('content')
<livewire:authentication.edit-profile :theme=$theme :organization=$organization />
@endsection