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
                <div class="ui twelve wide column">
                    <div class="ui mini three statistics">
                        <div class="{{ $playlist->kd() >= 1.0 ? 'green' : 'red' }} statistic">
                            <div class="value">
                                {{ $playlist->kd() }}
                            </div>
                            <div class="label">
                                KD Ratio
                            </div>
                        </div>
                        <div class="{{ $playlist->kad() >= 1.0 ? 'green' : 'red' }} statistic">
                            <div class="value">
                                {{ $playlist->kad() }}
                            </div>
                            <div class="label">
                                KAD Ratio
                            </div>
                        </div>
                        <div class="{{ $playlist->winRateColor() }} statistic">
                            <div class="value">
                                {{ $playlist->winRate() }}%
                            </div>
                            <div class="label">
                                Win Rate
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach