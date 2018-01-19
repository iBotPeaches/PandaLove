<div class="ui seven statistics">
    <div class="{{ $stats->{$key . '_top1'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top1'} }}
        </div>
        <div class="label">
            Wins
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top3'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top3'} }}
        </div>
        <div class="label">
            Top 3
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top5'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top5'} }}
        </div>
        <div class="label">
            Top 5
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top6'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top6'} }}
        </div>
        <div class="label">
            Top 6
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top10'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top10'} }}
        </div>
        <div class="label">
            Top 10
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top12'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top12'} }}
        </div>
        <div class="label">
            Top 12
        </div>
    </div>
    <div class="{{ $stats->{$key . '_top25'} > 0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $stats->{$key . '_top25'} }}
        </div>
        <div class="label">
            Top 25
        </div>
    </div>
</div>
<br />
<div class="ui three statistics">
    <div class="statistic">
        <div class="value">
            {{ number_format($stats->{$key . '_kills'}) }}
        </div>
        <div class="label">
            Kills
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($stats->{$key . '_matchesplayed'}) }}
        </div>
        <div class="label">
            Matches
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($stats->{$key . '_score'}) }}
        </div>
        <div class="label">
            Score
        </div>
    </div>
</div>
<div class="ui info message">
    Time Played: <strong>{{ Onyx\Laravel\Helpers\Text::timeDuration(($stats->{$key . '_minutesplayed'} * 60), 'dhm') }}</strong>,
    Last Played: <strong>{{ $stats->{$key . '_lastmodified'}->diffForHumans() }}</strong>
</div>