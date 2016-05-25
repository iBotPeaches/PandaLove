@for ($i = 0; $i < $data['roundCount']; $i++)
    <h3 class="ui top attached header">
        Round {{ $i + 1 }}
        @if (isset($data['rounds'][$i]['zombiesWin']) && $data['rounds'][$i]['zombiesWin'])
            <span class="ui green label">Zombies Win</span>
        @elseif (isset($data['rounds'][$i]['humansWin']) && $data['rounds'][$i]['humansWin'])
            <span class="ui blue label">Human(s) Survived</span>
        @endif
    </h3>
    <div class="ui attached segment">
        @include('includes.halo5.game.events.round-table', ['round' => $data['data'][$i], 'i' => $i])
    </div>
@endfor

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".ui.sortable.table").tablesort();
        });
    </script>
@append