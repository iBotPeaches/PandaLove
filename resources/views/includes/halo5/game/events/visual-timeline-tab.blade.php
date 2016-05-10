<section id="cd-timeline">
    @foreach ($combined as $time)
        <div class="cd-timeline-block">
            <div class="cd-timeline-img" style="background: #333;">
                <img src="{{ asset('images/unknown-weapon.png') }}" />
            </div>
            <div class="cd-timeline-content">
                <h2>Gamertag</h2>
                <p>Paragraph</p>
                <span class="cd-date">{{ $time['stats']['time'] }}</span>
            </div>
            @foreach ($time as $user_id => $items)
                @if ($user_id != "stats")
                    @foreach ($items as $item)
                    @endforeach
                @endif
            @endforeach
        </div>
    @endforeach
</section>