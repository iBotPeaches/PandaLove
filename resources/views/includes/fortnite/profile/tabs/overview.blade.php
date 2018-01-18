<div class="ui attached segment">
    <div class="row">
        <div class="4u">
            <div class="ui cards">
                <div class="ui card">
                    <div class="image">
                        <img src="{{ asset('images/fortnite-default.png') }}" />
                    </div>
                    <div class="content">
                        <a class="header">{{ $account->gamertag }}</a>
                        <div class="meta">
                            <span class=""></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="8u">
            <div class="ui three statistics">
                <div class="{{ $stats->solo_top1 > 0 ? 'green' : 'red' }} statistic">
                    <div class="value">
                        {{ $stats->solo_top1 }}
                    </div>
                    <div class="label">
                        Solo Wins
                    </div>
                </div>
                <div class="{{ $stats->duor_top1 > 0 ? 'green' : 'red' }} statistic">
                    <div class="value">
                        {{ $stats->duo_top1 }}
                    </div>
                    <div class="label">
                        Duo Wins
                    </div>
                </div>
                <div class="{{ $stats->squad_top1 > 0 ? 'green' : 'red' }} statistic">
                    <div class="value">
                        {{ $stats->squad_top1 }}
                    </div>
                    <div class="label">
                        Squad Wins
                    </div>
                </div>
            </div>
            <div class="ui three statistics">
                <div class="blue statistic">
                    <div class="value">
                        {{ $stats->solo_matchesplayed }}
                    </div>
                    <div class="label">
                        Solo Matches
                    </div>
                </div>
                <div class="blue statistic">
                    <div class="value">
                        {{ $stats->duo_matchesplayed }}
                    </div>
                    <div class="label">
                        Duo Matches
                    </div>
                </div>
                <div class="blue statistic">
                    <div class="value">
                        {{ $stats->squad_matchesplayed }}
                    </div>
                    <div class="label">
                        Squad Matches
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