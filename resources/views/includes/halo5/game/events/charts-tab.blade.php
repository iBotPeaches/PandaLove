<h2 class="ui header">Kills over time</h2>
<canvas id="killsOverTime"></canvas>

@section('inline-js')
    <script src="{{ asset("js/chart.js") }}"></script>
    <script type="text/javascript">
        $(function() {
            var ctx = $("#killsOverTime");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: jQuery.parseJSON('{!! $chart_data !!}'),
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        });
    </script>
@append