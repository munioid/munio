@extends($theme.'.layouts.app')
@section('title', 'Detail Reservasi')

@section('content')
<x-event.reservation-detail :theme=$theme :reservation=$reservation />
@endsection