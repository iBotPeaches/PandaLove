<div class="ui four statistics">
    <div class="{{ $account->h5->kd() > 1.0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $account->h5->kd() }}
        </div>
        <div class="label">
            KD Ratio
        </div>
    </div>
    <div class="{{ $account->h5->kad() > 1.0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $account->h5->kad() }}
        </div>
        <div class="label">
            KAD Ratio
        </div>
    </div>
    <div class="{{ $account->h5->winRateColor() }} statistic">
        <div class="value">
            {{ $account->h5->winRate() }}%
        </div>
        <div class="label">
            Win Rate
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->totalHeadshots) }}
        </div>
        <div class="label">
            Total Headshots
        </div>
    </div>
</div>
<div class="ui four statistics">
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->totalGames) }}
        </div>
        <div class="label">
            Total Games
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->totalKills) }}
        </div>
        <div class="label">
            Total Kills
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->totalAssists) }}
        </div>
        <div class="label">
            Total Assists
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->totalDeaths) }}
        </div>
        <div class="label">
            Total Deaths
        </div>
    </div>
</div>
<div class="ui info message">
    Playtime: <strong>{{ \Onyx\Destiny\Helpers\String\Text::timeDuration($account->h5->totalTimePlayed) }}</strong>
</div>