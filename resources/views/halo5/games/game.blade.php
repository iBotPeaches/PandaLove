@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        {{ $match->gametype->name }} on {{ $match->map->name }}
                    </h1>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
@append