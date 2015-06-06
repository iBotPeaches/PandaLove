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
                <td>
                    {!! $game->game->title() !!}&nbsp;<a href="{{ $game->url }}">{{ $game->game->type()->title }}</a>
                </td>
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