<div class="{{ $playlist->kd() >= 1.0 ? 'green' : 'red' }} statistic">
    <div class="value">
        {{ $playlist->kd() }}
    </div>
    <div class="label">
        KD Ratio
    </div>
</div>