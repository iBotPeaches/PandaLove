<h3 class="ui top attached header">
    All Time Top CSR
</h3>
<div class="ui attached segment">
    <div class="row">
        <div class="4u">
            <div class="ui cards">
                <div class="ui card">
                    <div class="image">
                        <img src="{{ $playlist->high_csr->tiers->{$playlist->tier('highest')} }}" />
                    </div>
                    <div class="content">
                        <a class="header">{{ $playlist->stock->name }}</a>
                        <div class="meta">
                            <span class="">{{ $playlist->title('highest') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="8u">
            @if ($playlist->measurementMatchesLeft > 0)
                <div class="ui progress rank-placement" data-value="{{ $playlist->getGamesDone() }}" data-total="10">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label">Matches till Rank Placement in {{ $playlist->stock->name }}</div>
                </div>
                <div class="ui divider"></div>
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
                    <div class="ui divider"></div>
                @endif
            @endif
            @if ($progressBar['next'] == null)
                <div class="ui progress success" data-value="100" data-total="100" id="spartan-rank-progress">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label">Max Level (152) Achieved!</div>
                </div>
            @else
                <div class="ui blue progress" data-value="{{ $progressBar['current'] }}" data-total="{{ $progressBar['max'] }}" id="spartan-rank-progress">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label">Progress to Level {{ $progressBar['next']->level }}</div>
                </div>
            @endif
            <div class="ui icon message" id="update-message">
                <i class="notched circle loading icon"></i>
                <div class="content">
                    <div class="header">
                        Just one second
                    </div>
                    <p>
                        Checking if this profile needs an update.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $('#spartan-rank-progress')
                .progress({
                    label: 'ratio',
                    text: {
                        ratio: "{{ number_format($progressBar['current']) . " / " . number_format($progressBar['max']) . " Xp" }}"
                    }
                })
        ;
    </script>
@append