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
                    title: {
                        display: true,
                        text: "Kills over time",
                        fontSize: 15
                    },
                    legend: {
                        labels: {
                            boxWidth: 20
                        }
                    },
                    tooltips: {
                        callbacks: {
                            afterBody: function(tooltipItem, data) {
                                var index = tooltipItem[0].yLabel;
                                var teamIndex = tooltipItem[0].datasetIndex;
                                var teamKey = data['datasets'][teamIndex]['team_id'];
                                console.log(index, teamIndex, teamKey);

                                if (typeof data['killfeed'][index][teamKey] !== 'undefined') {
                                    return data['killfeed'][index][teamKey];
                                }
                                return "";
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: "Kills"
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: "Match Time"
                            }
                        }]
                    }
                }
            });
        });
    </script>
@append