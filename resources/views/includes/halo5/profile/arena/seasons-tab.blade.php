
<?php

/** @var $playlist \Onyx\Halo5\Objects\PlaylistData */

?>
@foreach ($seasons as $season)
    <h3 class="ui top attached header">
        {{ $season['season']->name }}
    </h3>
    <div class="ui attached segment">
        @foreach ($season['playlists'] as $playlist)
            <div class="ui grid">
                <div class="ui four wide column">
                    <a class="header">{{ $playlist->stock->name }}</a>
                    <ul class="ui list">
                        <li>{{ $playlist->title('highest') }}</li>
                        @if ($playlist->highest_Csr != 0)
                            <li>CSR - <strong>{{ number_format($playlist->highest_Csr) }}</strong></li>
                            @if ($playlist->highest_rank != 0)
                                <li><strong>{{ $playlist->rank('highest') }}</strong> in world.</li>
                            @endif
                        @endif
                    </ul>
                </div>
                <div class="ui twelve wide column mobile tablet only">
                    <div class="ui mini three statistics">
                        @include('includes.halo5.profile.arena.statistics.kd')
                        @include('includes.halo5.profile.arena.statistics.kda')
                        @include('includes.halo5.profile.arena.statistics.win_rate')
                    </div>
                </div>
                <div class="ui twelve wide column computer only">
                    <div class="ui mini five statistics">
                        @include('includes.halo5.profile.arena.statistics.kd')
                        @include('includes.halo5.profile.arena.statistics.kda')
                        @include('includes.halo5.profile.arena.statistics.win_rate')
                        @include('includes.halo5.profile.arena.statistics.percentile')
                        @include('includes.halo5.profile.arena.statistics.games')
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach