<div class="ui raised black segment">
    <table class="ui sortable table">
        <thead class="desktop only">
        <tr>
            <th>Gamertag</th>
            <th>Medals</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($players as $player)
            <tr>
                <td class="{{ $player->dnf == 1 ? 'strikethrough-css' : null }}" style="min-width: 220px">
                    @if ($match->isArena())
                        <span class="right floated author">
                                <img class="ui avatar image arena-popup" src="{{ $player->getArenaImage() }}" data-content="{{ $player->getArenaTooltip() }}"/>
                                <a href="{{ URL::action('Halo5\ProfileController@index', [$player->account->seo]) }}">
                                    {{ $player->account->gamertag }}
                                </a>
                            </span>
                    @else
                        <a href="{{ URL::action('Halo5\ProfileController@index', [$player->account->seo]) }}">
                            {{ $player->account->gamertag }}
                        </a>
                    @endif
                </td>
                <td>
                    @foreach ($player->medals as $key => $medal)
                        @if ($medal instanceof \Onyx\Halo5\Objects\Medal && $medal->count > 0)
                            <i class="medal tiny-medal medal-{{ $medal->contentId }}" data-title="{{ $medal->name }}" data-content="{{ $medal->description }}"></i>
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@section('inline-css')
    <style type="text/css">
        .tiny-medal {
            -moz-transform: scale(0.5,0.5);
            -ms-transform: scale(0.5,0.5);
            -webkit-transform: scale(0.5,0.5);
            -o-transform: scale(0.5,0.5);
            transform: scale(0.5,0.5);
            margin-top: -20px;
            margin-left: -15px;
        }
    </style>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.tiny-medal')
                    .popup({
                        inline   : true,
                        hoverable: true
                    })
            ;
        });
    </script>
@append