<div class="ui top attached tabular menu">
    <a class="{{ $characterId == false ? 'active' : null }} item" data-tab="overview">Overview</a>
    @foreach($account->characters as $char)
        <a class="{{ $characterId == $char->characterId ? 'active' : null }} item" data-tab="char_{{ $char->characterId }}">{{ $char->level }} {{$char->class->title}}</a>
    @endforeach
    @if (count($games) > 0)
        <a class="item" data-tab="unbroken-games">Unbroken Games</a>
    @endif
</div>
<div class="ui bottom attached {{ $characterId == false ? 'active' : null }} tab segment" data-tab="overview">
    @include('includes.profile.overview-tab')
</div>
@foreach($account->characters as $char)
    <div class="ui bottom attached {{ $characterId == $char->characterId ? 'active' : null }} tab segment" data-tab="char_{{ $char->characterId }}">
        @include('includes.profile.character', ['char' => $char])
    </div>
@endforeach
<div class="ui bottom attached tab segment" data-tab="unbroken-games">
    <table class="ui sortable table">
        <thead class="desktop only">
        <tr>
            <th>Game</th>
            <th>Kills</th>
            <th>Deaths</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
                <tr class="{{ $game->deaths == 0 ? 'positive' : null }}">
                    <td>{!! $game->game->title() !!}&nbsp;{{ $game->game->type()->title }}</td>
                    <td class="kills-table">{{ $game->kills }}</td>
                    <td class="deaths-table {{ $game->deaths == 0 ? 'no-deaths' : null }}">
                        {!! $game->deaths == 0 ? '<i class="smile icon"></i> no deaths' : $game->deaths !!}
                    </td>
                    <td>{{ $game->game->occurredAt }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item')
                    .tab()
            ;
        });
    </script>
@append