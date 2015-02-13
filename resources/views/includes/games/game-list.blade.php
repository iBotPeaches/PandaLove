<div class="ui divided list">
    @foreach($games as $raid)
        @if ($raid->raidTuesday != 0)
            <a class="item no_underline" href="{{ URL::action('GameController@getTuesday', [$raid->raidTuesday]) }}">
                @if ($raid->isHard)
                    <div class="right floated compact ui red button fb">Hard</div>
                @else
                    <div class="right floated compact ui green button fb">Normal</div>
                @endif
                <img class="ui avatar bordered image non-white-bg" src="{{ $raid->type()->extra }}" />
                <div class="content">
                    <div class="header">
                        {{ $raid->raidCount }} {{ $raid->type()->title }} Raids
                    </div>
                    <div class="description">
                        {{ $raid->occurredAt }}
                    </div>
                </div>
            </a>
        @else
            <a class="item no_underline" href="{{ URL::action('GameController@getGame', [$raid->instanceId]) }}">
                @if ($raid->isHard)
                    <div class="right floated compact ui red button fb">Hard</div>
                @else
                    <div class="right floated compact ui green button fb">Normal</div>
                @endif
                <img class="ui avatar bordered image non-white-bg" src="{{ $raid->type()->extra }}" />
                <div class="content">
                    <div class="header">
                        {{ $raid->type()->title }}
                    </div>
                    <div class="description">
                        {{ $raid->occurredAt }}
                    </div>
                </div>
            </a>
        @endif
    @endforeach
</div>