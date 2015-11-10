@extends('app')

@section('content')
    @include('includes.usercp.gamertag-verify')

    @if ($user->admin)
        @include('includes.usercp.admin-add-destiny')
        @include('includes.usercp.admin-add-h5')
    @endif
@endsection