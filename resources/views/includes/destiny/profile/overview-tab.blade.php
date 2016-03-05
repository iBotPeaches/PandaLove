<div class="ui statistics">
    <div class="statistic">
        <div class="value">
            {{ $account->destiny->grimoire }}
        </div>
        <div class="label">
            Grimoire
        </div>
    </div>
    <!--
    <div class="statistic">
        <div class="value">
            {{ $account->destiny->glimmer }}
        </div>
        <div class="label">
            Glimmer
        </div>
    </div>
    <div class="statistic">
        <div class="value">
            {{ $account->destiny->legendary_marks }}
        </div>
        <div class="label">
            Legendary Marks
        </div>
    </div>-->
    @if ($account->clanName != "")
        <div class="blue statistic">
            <div class="text value">
                {{ $account->destiny->clanName }}
            </div>
            <div class="label">
                Clan
            </div>
        </div>
    @endif
    <div class="statistic">
        <div class="value">
            {{ $account->destiny->charsAbove(40) }}
        </div>
        <div class="label">
            Characters at 40
        </div>
    </div>
</div>
<div class="row">
    <div class="12u">
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