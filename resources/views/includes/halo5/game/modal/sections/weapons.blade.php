@define $i = 0
@foreach ($player->weapons as $key => $weapon)
    @if ($weapon instanceof \Onyx\Halo5\Objects\Weapon && $weapon->count > 0)
        @if ($i == 0)
            <div class="ui six doubling cards">
        @endif
        <div class="card">
            <div class="image">
                @if (isset($weapon->name))
                    <img src="/images/weapons/{{ $weapon->uuid }}.png" />
                @else
                    <img src="/images/unknown-weapon.png" />
                @endif
            </div>
            <div class="content">
                <div class="header">{{ $weapon->name or 'Unknown Weapon' }}</div>
                <div class="meta">
                    {{ number_format($weapon->count) }} Kills
                </div>
            </div>
        </div>
        @if ($i == 5)
            @define $i = -1
            </div>
        @endif
        @define $i++
    @endif
@endforeach
@if ($i != 0)
    </div>
@endif