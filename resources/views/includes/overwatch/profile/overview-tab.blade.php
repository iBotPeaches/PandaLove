<h3 class="ui top attached header">
    Overall Stats
</h3>
<div class="ui attached segment">
    <div class="row">
        <div class="4u">
            <div class="ui cards">
                <div class="ui card">
                </div>
            </div>
        </div>
        <div class="8u">
            <div class="ui blue progress" data-value="{{ $overall->level }}" data-total="100" id="overwatch-rank-progress">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">Level {{ $overall->level}} out of 100 of Prestige {{ $overall->prestige }}</div>
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