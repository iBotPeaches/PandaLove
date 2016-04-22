<div class="ui modal {{ $player->id . "-dropdown" }}">
    <i class="close icon"></i>
    <div class="header">
        {{ $player->account->gamertag }}
    </div>
    <div class="content">
        @if (count($player->medals) > 0)
            <h3 class="ui header">Medals Earned</h3>
            @include('includes.halo5.game.modal.sections.medals')
            <div class="ui divider"></div>
        @endif

        @if (count($player->weapons) > 0)
            <h3 class="ui header">Weapons Used</h3>
            @include('includes.halo5.game.modal.sections.weapons')
            <div class="ui divider"></div>
        @endif

        @if (count($player->killed) > 0 || count($player->killed_by) > 0)
            <h3 class="ui header">Killed & Killed By</h3>
            @include('includes.halo5.game.modal.sections.killed')
            <div class="ui divider"></div>
        @endif

        @if (count($player->impulses) > 0)
            <h3 class="ui header">Awards</h3>
            @include('includes.halo5.game.modal.sections.impulses')
        @endif
    </div>
</div>