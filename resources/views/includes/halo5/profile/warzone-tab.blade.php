@if ($account->h5->warzone != null && $account->h5->warzone->totalKills != 0)
    <div class="ui stackable secondary pointing menu">
        <a class="active item" data-tab="warzone-overview">
            Stat Overview
        </a>
        <a class="item" data-tab="warzone-medals">
            Medals
        </a>
        <a class="item" data-tab="warzone-weapons">
            Weapons
        </a>
    </div>
    <div class="ui bottom attached active tab" data-tab="warzone-overview">
        @include('includes.halo5.profile.warzone.overview-tab')
    </div>
    <div class="ui bottom attached tab" data-tab="warzone-medals">
        @include('includes.halo5.profile.warzone.medals-tab', ['mMedals' => $account->h5->warzone->medals])
    </div>
    <div class="ui bottom attached tab" data-tab="warzone-weapons">
        @include('includes.halo5.profile.warzone.weapons-tab')
    </div>
@else
    <div class="ui warning message">
        No warzone data yet. This will remove itself after it has downloaded Warzone data.
    </div>
@endif