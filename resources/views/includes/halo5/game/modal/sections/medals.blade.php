@define $i = 0
@foreach ($player->medals as $key => $medal)
    @if ($medal instanceof \Onyx\Halo5\Objects\Medal && $medal->count > 0)
        @if ($i == 0)
            <div class="ui ten statistics">
        @endif
        <div class="ui statistic">
            <div class="value stat-fix">
                <i class="medal medal-{{ $medal->contentId }}" data-title="{{ $medal->name }}" data-content="{{ $medal->description }}"></i>
            </div>
            <div class="label">
                {{ number_format($medal->count) }}
            </div>
        </div>
        @if ($i == 9)
            </div>
            @define $i = -1
        @endif
        @define $i++
    @endif
@endforeach
@if ($i != 0)
    </div>
@endif
