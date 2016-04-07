@if ($account->h5 != null && $account->h5->totalKills != 0)
    <div class="ui stackable secondary pointing menu">
        <a class="active item" data-tab="arena-overview">
            Stat Overview
        </a>
        <a class="item" data-tab="arena-playlists">
            Playlists
        </a>
        <a class="item" data-tab="arena-seasons">
            Seasons
        </a>
        <a class="item" data-tab="arena-medals">
            Medals
        </a>
        <a class="item" data-tab="arena-weapons">
            Weapons
        </a>
    </div>
    <div class="ui bottom attached active tab" data-tab="arena-overview">
        @include('includes.halo5.profile.arena.overview-tab')
    </div>
    <div class="ui bottom attached tab" data-tab="arena-playlists">
        @include('includes.halo5.profile.arena.playlists-tab')
    </div>
    <div class="ui bottom attached tab" data-tab="arena-seasons">
        @include('includes.halo5.profile.arena.seasons-tab')
    </div>
    <div class="ui bottom attached tab" data-tab="arena-medals">
        @include('includes.halo5.profile.arena.medals-tab', ['mMedals' => $account->h5->medals])
    </div>
    <div class="ui bottom attached tab" data-tab="arena-weapons">
        @include('includes.halo5.profile.arena.weapons-tab')
    </div>
@else
    <div class="ui warning message">
        No Arena data yet. This will remove itself after it has downloaded Warzone data.
    </div>
@endif