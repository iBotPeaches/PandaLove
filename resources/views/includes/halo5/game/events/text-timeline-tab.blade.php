<div class="ui middle aligned divided list">
    @foreach($match->events as $event)
        <div class="item">
            <div class="right floated content">
                {{ $event->seconds_since_start }}
            </div>
            @include('includes.halo5.game.events.types.' . \Onyx\Halo5\Enums\EventName::getSeo($event->event_name))
        </div>
    @endforeach
</div>