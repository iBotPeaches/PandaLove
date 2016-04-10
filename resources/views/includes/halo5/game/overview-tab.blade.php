@if (count($match->teams) > 1)
    <div class="ui {{ \Onyx\Laravel\Helpers\Text::numberToWord(count($match->teams)) }} column stackable grid">
        @foreach ($match->teams as $team)
            <div class="column">
                <div class="ui fluid {{ $team->team->getSemanticColor() }} card">
                    <div class="image">
                        <img src="{{ $team->team->getImage() }}" />
                    </div>
                    <div class="content">
                        <span class="header">{!! $team->label() !!} {{ $team->team->name }} - {{ $team->score }}</span>
                    </div>
                    <div class="extra content">

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else

@endif