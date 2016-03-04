@define $i = 0
@foreach ($account->h5->warzone->weapons as $key => $count)
    @if ($count > 0)
        @if ($i == 0)
            <div class="ui four doubling cards">
        @endif
                <div class="card">
                    <div class="image">
                        @if (isset($weapons[$key]->name))
                            <img src="/images/weapons/{{ $weapons[$key]->uuid }}.png" />
                        @else
                            <img src="/images/unknown-weapon.png" />
                        @endif
                    </div>
                    <div class="content">
                        <div class="header">{{ $weapons[$key]->name or 'Unknown Weapon' }}</div>
                        <div class="meta">
                            {{ number_format($count) }} Kills
                        </div>
                    </div>
                </div>
        @if ($i == 3)
            @define $i = -1
            </div>
        @endif
        @define $i++
    @endif
@endforeach

@if ($i != 0)
    </div>
@endif