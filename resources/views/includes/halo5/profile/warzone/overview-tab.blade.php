<div class="ui four statistics">
    <div class="{{ $account->h5->warzone->kd() > 1.0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $account->h5->warzone->kd() }}
        </div>
        <div class="label">
            KD Ratio
        </div>
    </div>
    <div class="{{ $account->h5->warzone->kad() > 1.0 ? 'green' : 'red' }} statistic">
        <div class="value">
            {{ $account->h5->warzone->kad() }}
        </div>
        <div class="label">
            KAD Ratio
        </div>
    </div>
    <div class="{{ $account->h5->warzone->winRateColor() }} statistic">
        <div class="value">
            {{ $account->h5->warzone->winRate() }}%
        </div>
        <div class="label">
            Win Rate
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->warzone->totalHeadshots) }}
        </div>
        <div class="label">
            Total Headshots
        </div>
    </div>
</div>
<div class="ui four statistics">
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->warzone->totalGames) }}
        </div>
        <div class="label">
            Total Games
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->warzone->totalKills) }}
        </div>
        <div class="label">
            Total Kills
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->warzone->totalAssists) }}
        </div>
        <div class="label">
            Total Assists
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ number_format($account->h5->warzone->totalDeaths) }}
        </div>
        <div class="label">
            Total Deaths
        </div>
    </div>
</div>
<div class="ui info message">
    Warzone Playtime: <strong>{{ \Onyx\Destiny\Helpers\String\Text::timeDuration($account->h5->warzone->totalTimePlayed) }}</strong>
</div>