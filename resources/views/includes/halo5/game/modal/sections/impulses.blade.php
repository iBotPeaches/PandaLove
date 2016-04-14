<div class="ui middle aligned selection list">
    @foreach ($player->impulses as $impulse)
        @if ($impulse instanceof \Onyx\Halo5\Objects\Impulse)
            <div class="item">
                <i class="right triangle icon"></i>
                <div class="content">
                    <div class="header">{{ $impulse->name }}</div>
                    <div class="description">{{ $impulse->count }}</div>
                </div>
            </div>
        @endif
    @endforeach
</div>