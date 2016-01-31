@foreach($playlists as $playlist)
    <div class="ui segment">
        <div class="row">
            <div class="6u">
                <div class="ui two cards">
                    <div class="ui card">
                        <div class="image">
                            <div class="ui black ribbon label">
                                Highest
                            </div>
                            <img src="{{ $playlist->high_csr->tiers->{$playlist->tier('highest')} }}" />
                        </div>
                        <div class="content">
                            <a class="header">{{ $playlist->stock->name }}</a>
                            <div class="meta">
                                <span class="">{{ $playlist->title('highest') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ui card">
                        <div class="image">
                            <div class="ui blue ribbon label">
                                Current
                            </div>
                            <img src="{{ $playlist->current_csr->tiers->{$playlist->tier('current')} }}" />
                        </div>
                        <div class="content">
                            <a class="header">{{ $playlist->stock->name }}</a>
                            <div class="meta">
                                <span class="">{{ $playlist->title('current') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="6u">
                @if ($playlist->measurementMatchesLeft > 0)
                    <div class="ui progress rank-placement" data-value="{{ $playlist->getGamesDone() }}" data-total="10">
                        <div class="bar">
                            <div class="progress"></div>
                        </div>
                        <div class="label">Matches till Rank Placement in {{ $playlist->stock->name }}</div>
                    </div>
                @else
                    @if ($playlist->highest_Csr != 0)
                        <div class="ui black segment">
                            <div class="ui top attached label">Highest CSR Obtained - {{ number_format($playlist->highest_Csr) }}</div>
                            @if ($playlist->highest_rank != 0)
                                <div class="ui green message">
                                    I placed <strong>{{ $playlist->rank('highest') }}</strong> in this playlist at one time.
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="ui blue segment">
                        <div class="ui top attached label">{{ $playlist->current_Csr == 0 ? 'Current' : 'Current CSR Obtained - ' . number_format($playlist->current_Csr) }}</div>
                        @if ($playlist->current_percentNext != 0)
                            <div class="ui blue progress percent-next" data-value="{{ $playlist->current_percentNext }}" data-total="100">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label">Percent to next tier</div>
                            </div>
                        @endif
                        @if ($playlist->current_rank != 0)
                            <div class="ui green message">
                                I'm currently <strong>{{ $playlist->rank('current') }}</strong> in this playlist.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach