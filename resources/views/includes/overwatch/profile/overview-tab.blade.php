<h3 class="ui top attached header">
    Overall Stats
</h3>
<div class="ui attached segment">
    <div class="row">
        <div class="12u">
            <div class="ui blue progress" data-value="{{ $overall->level }}" data-total="100" id="overwatch-rank-progress">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">Level {{ $overall->level}} out of 100 of Prestige {{ $overall->prestige }}</div>
            </div>
            <div class="ui four statistics">
                <div class="statistic">
                    <div class="value">
                        {{ $overall->comprank }}
                    </div>
                    <div class="label">
                        SR (Current)
                    </div>
                </div>
                <div class="statistic">
                    <div class="value">
                        {{ $overall->max_comprank }}
                    </div>
                    <div class="label">
                        SR (Max)
                    </div>
                </div>
                <div class="statistic">
                    <div class="value">
                        {{ $overall->eliminations_avg }}
                    </div>
                    <div class="label">
                        Elims (Avg)
                    </div>
                </div>
                <div class="statistic">
                    <div class="value">
                        {{ $overall->deaths_avg }}
                    </div>
                    <div class="label">
                        Deaths (Avg)
                    </div>
                </div>
            </div>
            <div class="ui icon message" id="update-message">
                <i class="notched circle loading icon"></i>
                <div class="content">
                    <div class="header">
                        Just one second
                    </div>
                    <p>
                        Checking if this profile needs an update.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $('#overwatch-rank-progress')
            .progress({
                label: 'ratio',
                text: {
                    ratio: "{{ $overall->level }}%"
                }
            })
        ;
    </script>
@append