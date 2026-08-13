@extends($theme.'.layouts.app')

@section('title', 'Berita')

@section('content')
<livewire:blog.posts :categories=$categories :tags=$tags />
@endsection