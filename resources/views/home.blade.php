@extends('app')

@section('content')
    <div class="ui one column centered grid" style="padding-top: 20px;">
        <img class="big-image" src="{{ asset('images/panda-logo.png') }}" />
    </div>
@endsection

@section('inline-css')
    <script type="text/css">
        .big-image {
            width: 100%;
            min-width: 484px;
            min-height: 720px;
        }
    </script>
@endsection