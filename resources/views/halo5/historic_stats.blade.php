@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Welcome to our <strong>Graph</strong></h1>
                    </header>
                    <?= dd($stats); ?>
                    <div id="arena_chart_container"></div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script src="//code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        $(function() {
            // series: [{
            // name: Gamertag
            // data: [
            // [kd, kda, totalGames]
            // ]},

            $("#arena_chart_container").highcharts({
                title: {
                    text: 'Arena Historical Stats',
                    x: -20
                },
                xAxis: {

                }
            });
        })
    </script>
@append