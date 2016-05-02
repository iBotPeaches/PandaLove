<div class="{{ $playlist->percentileColor() }} statistic csr-popup"
     data-title="What is CSR Percentile?"
     data-html="This means in <strong>{{ $playlist->stock->name . " - " . $season['season']->name }} </strong>. <strong>{{ $account->gamertag }}</strong> is in the top <strong>{{ $playlist->csrPercentile }}</strong> of players.">
    <div class="value">
        {{ $playlist->csrPercentile }}
    </div>
    <div class="label">
        CSR %
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.csr-popup').popup();
        });
    </script>
@append