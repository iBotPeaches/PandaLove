@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Welcome to our <strong>Graph</strong></h1>
                    </header>
                    <div class="ui secondary menu" id="graph-tabs">
                        <div class="item active" data-tab="overview">Overview</div>
                        @foreach($graphs as $graph)
                            <div class="item" data-tab="{{ $graph['slug'] }}">{{ $graph['title'] }}</div>
                        @endforeach
                    </div>
                    <div class="ui tab active" data-tab="overview">
                        @include('includes.halo5.stats._overview')
                    </div>
                    @foreach($graphs as $graph)
                        <div class="ui tab" data-tab="{{ $graph['slug'] }}" data-path="{{ $graph['slug'] }}">

                        </div>
                    @endforeach
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
    <link rel="stylesheet" href="{{ asset('css/c3.min.css') }}" />
    <style type="text/css">
        .graph {
            width: 100%;
            height: 650px;
        }
    </style>
@append

@section('inline-js')
    <script src="//d3js.org/d3.v3.min.js"></script>
    <script src="{{ asset("js/c3.min.js") }}"></script>
    <script type="text/javascript">
        $(function() {
           $('.menu .item').tab({
               auto: true,
               cache: false,
               evaluteScripts: true,
               path: '/h5/stats/individual-graph/'
           });
        });
    </script>
@append