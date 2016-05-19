<ul class="timeline">
    @foreach ($combined as $time)
        <li>
            <div class="timeline-badge primary"><a><i class="ui icon bullseye" rel="tooltip" title="{{ $time['stats']['time'] }}"></i></a></div>
            <div class="timeline-panel">
                <div class="timeline-body">
                    <div class="ui middle aligned divided list">
                        @foreach ($time as $user_id => $items)
                            @if ($user_id !== "stats")
                                @foreach ($items as $item)
                                    <div class="item">
                                        @include('includes.halo5.game.events.types.' . \Onyx\Halo5\Enums\EventName::getSeo($item->event_name), ['event' => $item])
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="timeline-footer">
                    {{ $time['stats']['time'] }} into match.
                </div>
            </div>
        </li>
    @endforeach
    <li class="clearfix" style="float: none;"></li>
</ul>