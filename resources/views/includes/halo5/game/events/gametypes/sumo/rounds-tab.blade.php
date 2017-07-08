@for ($i = 0; $i < $data['roundCount']; $i++)
    <h3 class="ui top attached header">
        Round {{ $i + 1 }}
        <span class="ui {{ $data['team'][$data['roundWinners'][$i]]['team']->getSemanticColor() }} label">
            {{ $data['team'][$data['roundWinners'][$i]]['team']->team->name }} Win
        </span>
    </h3>
    <div class="ui attached segment">
        <div class="ui equal width stackable grid">
            @foreach ($data['data'][$i] as $team_id => $team)
                <div class="{{ $data['team'][$team_id]['team']->getSemanticColor() }} column">
                    @include('includes.halo5.game.events.gametypes.sumo.round-table', ['round' => $data['data'][$i][$team_id], 'i' => $i, 'team_id' => $team_id])
                </div>
            @endforeach
        </div>
    </div>
@endfor

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".ui.sortable.table").tablesort();
        });
    </script>
@append