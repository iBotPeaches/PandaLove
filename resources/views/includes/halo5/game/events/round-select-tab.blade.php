@if ($match->gametype->isBreakout())
    @include('includes.halo5.game.events.gametypes.breakout.rounds-tab')
@else
    @include('includes.halo5.game.events.gametypes.infection.rounds-tab')
@endif