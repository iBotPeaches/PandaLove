<script type="text/javascript">
    d3.json('{{ $data['url']  }}', function(error, data) {
        var chart = c3.generate({
            bindto: '{{ $data['selector'] }}',
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
                    },
                    label: {
                        text: 'Date',
                        position: 'outer-center'
                    }
                },
                y: {
                    tick: {
                        format: d3.format('.2f')
                    },
                    label: {
                        text: '{{ $data['y_axis'] }}',
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
                },
                grouped: false
            },
            zoom: {
                enabled: true
            }
        });
    });
</script>