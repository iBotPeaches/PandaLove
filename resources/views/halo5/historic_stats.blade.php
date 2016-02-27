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
    <script type="text/javascript">
        d3.json("{{ action('Halo5\StatsController@getArenaStats') }}", function(error, data) {
            var chart = c3.generate({
                bindto: '#arena_chart',
                data: {
                    x: 'x',
                    xFormat: '%Y-%m-%d %H:%M:%S',
                    json: data.c3
                },
                axis: {
                    x: {
                        type: 'timeseries',
                        tick: {
                            format: '%b %e'
                        }
                    },
                    y: {
                        tick: {
                            format: d3.format('.2f')
                        },
                        label: {
                            text: 'KD Ratio',
                            position: 'outer-middle'
                        }
                    }
                },
                legend: {
                    position: 'right'
                },
                tooltip: {
                    format: {
                        value: function(value, ratio, id, index) {
                            return '(' + value + ') - ' + data['totalGames'][id][index] + ' Games Played';
                        },
                        title: function(x) {
                            return 'Date: ' + x;
                        }
                    }
                }
            });
        });

        d3.json("{{ action('Halo5\StatsController@getWarzoneStats') }}", function(error, data) {
            var chart = c3.generate({
                bindto: '#warzone_chart',
                data: {
                    x: 'x',
                    xFormat: '%Y-%m-%d %H:%M:%S',
                    json: data
                },
                groups: [
                        ['1_kd', '1_kda']
                ],
                axis: {
                    x: {
                        type: 'timeseries',
                        tick: {
                            format: '%b %e'
                        }
                    }
                }
            });
        });
    </script>
@append