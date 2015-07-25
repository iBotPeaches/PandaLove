<div class="ui divided list">
    @foreach($games as $raid)
        @if ($raid->raidTuesday != 0)
            <a class="item no_underline" href="{{ URL::action('GameController@getTuesday', [$raid->raidTuesday]) }}">
                @if ($raid->isHard)
                    <div class="right floated compact ui red button fb">Hard</div>
                @else
                    <div class="right floated compact ui green button fb">Normal</div>
                @endif
                <img class="ui avatar bordered image non-white-bg pve-emblem" src="{{ $raid->type()->extra }}" />
                <div class="content">
                    <div class="header">
                        {{ $raid->raidCount }} {{ $raid->type()->title }} Raids
                    </div>
                    <div class="description">
                        {{ $raid->occurredAt }}
                    </div>
                </div>
            </a>
        @elseif ($raid->passageId != 0)
            <a class="item no_underline" href="{{ URL::action('GameController@getPassage', [$raid->passageId]) }}">
                <!--<div class="right floated compact ui blue button fb">{{ $raid->pvp->gametype }}</div>-->
                <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $raid->type()->extra }}" />
                <div class="content">
                    <div class="header">
                        {{ $raid->gameCount }} Games on {{ $raid->message == false ? $raid->type()->title : $raid->message }} with
                        @foreach ($raid->pandas() as $index => $panda)
                            {{ $panda->account->gamertag }}{{ $index < 2 ? ',' : null }}
                        @endforeach
                    </div>
                    <div class="description">
                        {{ $raid->occurredAt }}
                    </div>
                </div>
            </a>
        @else
            <a class="item no_underline" href="{{ URL::action('GameController@getGame', [$raid->instanceId]) }}">
                @if ($raid->type != "PVP" && $raid->type != 'PoE')
                    @if ($raid->isHard)
                        <div class="right floated compact ui red button fb">Hard</div>
                    @else
                        <div class="right floated compact ui green button fb">Normal</div>
                    @endif
                @elseif ($raid->type == "PVP")
                    <div class="right floated compact ui blue button fb">{{ $raid->pvp->gametype }}</div>
                @elseif ($raid->type == "PoE")
                    <div class="right floated compact ui purple button fb">Level {{ $raid->type()->extraThird }}</div>
                @endif
                @if ($raid->type == "PVP")
                    <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $raid->type()->extra }}" />
                @else
                    <img class="ui avatar bordered image non-white-bg pve-emblem" src="{{ $raid->type()->extra }}" />
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
        .pve-emblem {
            background: #000000 !important;
        }
    </style>
@append