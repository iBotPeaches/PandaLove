<table class="ui table">
    <thead class="desktop only">
    <tr>
        <th>Raid</th>
        <th>Date</th>
        <th>Completion Time</th>
        <th>PandaLove Members Present</th>
    </tr>
    </thead>
    <tbody>
    @foreach($raids as $raid)
        <tr>
            <td>
                @if ($raid->type == "PVP")
                    <img class="ui avatar bordered image non-white-bg pvp-emblem" src="{{ $raid->type()->extra }}" />
                @else
                    @if ($raid->isHard)
                        <div class="ui red horizontal label">Hard</div>
                    @else
                        <div class="ui green horizontal label">Normal</div>
                    @endif
                @endif
                @if ($raid->raidTuesday != 0)
                    <a href="{{ URL::action('GameController@getTuesday', [$raid->raidTuesday]) }}">
                        {{ $raid->type()->title }}
                    </a>
                @else
                    <a href="{{ URL::action('GameController@getGame', [$raid->instanceId]) }}">
                        {{ $raid->type()->title }}
                    </a>
                @endif
            </td>
            <td class="completed-table">{{ $raid->occurredAt }}</td>
            <td class="timetook-table">
                @if ($raid->raidTuesday != 0)
                    {{ \Onyx\Destiny\Helpers\String\Text::timeDuration($raid->totalTime) }}
                @else
                    {{ $raid->timeTookInSeconds }}
                @endif
            </td>
            <td class="pandacount-table">
                {{ $raid->completed() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="ui pagination menu">
    {!! with(new Onyx\Laravel\SemanticPresenter($raids))->render() !!}
</div>

@section('inline-css')
    <style type="text/css">
        .pvp-emblem {
            background: #9f342f !important;
        }
    </style>
@append