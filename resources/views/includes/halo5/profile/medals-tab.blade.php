@define $i = 0
@foreach ($medals as $medal)
    @if (isset($mMedals[$medal->contentId]) && $mMedals[$medal->contentId] > 0)
        @if ($i == 0)
            <div class="ui eight statistics">
        @endif
            <div class="ui statistic">
                <div class="value stat-fix">
                    <i class="medal medal-{{ $medal->contentId }}" data-title="{{ $medal->name }}" data-content="{{ $medal->description }}"></i>
                </div>
                <div class="label">
                    {{ $mMedals[$medal->contentId] }}
                </div>
            </div>
        @if ($i == 7)
            </div>
            @define $i = -1
        @endif
        @define $i++
    @endif
@endforeach
@if ($i != 1)
    </div>
@endif
