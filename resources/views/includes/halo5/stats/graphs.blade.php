<script type="text/javascript">
    @foreach ($graphs as $key => $vals)
        d3.json('{{ $vals['url']  }}', function(error, data) {
                var chart = c3.generate({
                    bindto: '{{ $key }}',
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
                                text: '{{ $vals['type'] }}',
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
                                var clean_id = id.substring(0, id.indexOf(' KD'));
                                return '(' + value + ') - ' + data['totalGames'][clean_id][index] + ' Games Played';
                            },
                            title: function(x) {
                                return 'Date: ' + x;
                            }
                        }
                    }
                });
            });
    @endforeach
</script>