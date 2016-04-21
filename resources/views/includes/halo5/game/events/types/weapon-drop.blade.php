<?php
/** @var $event \Onyx\Halo5\Objects\MatchEvent */
?>
@if (isset($event->killer) && $event->killer != null)
    <img class="ui avatar image" src="{{ $event->killer->h5_emblem->getEmblem() }}">
@endif
<div class="content">
    @if (isset($event->killer) && $event->killer != null)
        <a href="{{ action('Halo5\ProfileController@index', [$event->killer->seo]) }}">{{ $event->killer->gamertag }}</a>
    @else
        <a href="#">AI</a>
    @endif
    has dropped <b>{{ $event->killer_weapon->name }}</b>
    <img class="ui avatar image" src="{{ $event->killer_weapon->getImage() }}" />
    firing <b>{{ $event->shots_fired }}</b> shots and landing <b>{{ $event->shots_landed }}</b> of them.
</div>