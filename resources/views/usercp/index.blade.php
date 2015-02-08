@extends('app')

@section('content')
    @include('includes.usercp.gamertag-verify')

    @if ($user->admin)
        @include('includes.usercp.admin-add')
    @endif
@endsection