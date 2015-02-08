<div class="ui statistics">
    <div class="statistic">
        <div class="value">
            {{ $account->grimoire }}
        </div>
        <div class="label">
            Grimoire
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ $account->glimmer }}
        </div>
        <div class="label">
            Glimmer
        </div>
    </div>
    <div class="blue statistic">
        <div class="text value">
            {{ $account->clanName }}
        </div>
        <div class="label">
            Clan
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ $account->charsAbove(30) }}
        </div>
        <div class="label">
            Characters above 30
        </div>
    </div>
</div>