@if ($games['ResultCount'] != 0)
    <div class="ui five cards">
        @foreach ($games['Results'] as $result)
            <div class="card">
                <div class="content">
                    {{ $result['GameVariant']['ResourceId'] }}
                </div>
                <div class="content">
                    {{ $result['MapVariant']['ResourceId'] }}
                </div>
                <div class="content">
                    {{ $result['Id']['MatchId'] }}
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="ui warning message">
        No recent games. This will change once the user has played a game in Halo 5.
    </div>
@endif

@section('inline-js')

@append