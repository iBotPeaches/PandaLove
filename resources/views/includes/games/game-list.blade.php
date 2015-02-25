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
                @if ($raid->type != "PVP")
                    @if ($raid->isHard)
                        <div class="right floated compact ui red button fb">Hard</div>
                    @else
                        <div class="right floated compact ui green button fb">Normal</div>
                    @endif
                @elseif ($raid->type == "PVP")
                    <div class="right floated compact ui blue button fb">{{ $raid->gametype }}</div>
                @endif
                @if ($raid->type == "PVP")
                    <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $raid->type()->extra }}" />
                @else
                    <img class="ui avatar bordered image non-white-bg" src="{{ $raid->type()->extra }}" />
                @endif
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

@section('inline-css')
    <style type="text/css">
        .pvp-emblem {
            background: #9f342f !important;
        }
    </style>
@append