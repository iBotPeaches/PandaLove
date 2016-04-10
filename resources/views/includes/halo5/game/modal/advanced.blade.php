<div class="ui fullscreen modal {{ $player->uuid . "-dropdown" }}">
    <i class="close icon"></i>
    <div class="header">
        Advanced Stats - {{ $player->account->gamertag }}
    </div>
    <div class="content">
        <h3 class="ui header">Medals Earned</h3>
        @include('includes.halo5.game.modal.sections.medals')
        <div class="ui divider"></div>
        <h3 class="ui header">Weapons Used</h3>
        @include('includes.halo5.game.modal.sections.weapons')
        <div class="ui divider"></div>
        <h3 class="ui header">Killed & Killed By</h3>
    </div>
</div>