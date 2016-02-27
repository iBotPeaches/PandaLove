@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Welcome to our <strong>Graph</strong></h1>
                    </header>
                    <h4>Arena Stats</h4>
                    <div id="arena_chart" class="graph"></div>
                    <br />
                    <h4>Warzone Stats</h4>
                    <div id="warzone_chart" class="graph"></div>
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
    @include('includes.halo5.stats.graphs', ['data' => $graphs])
@append