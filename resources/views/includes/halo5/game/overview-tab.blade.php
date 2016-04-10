@if (count($match->teams) == 2)
    <div class="ui two column stackable grid">
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
@elseif (count($match->teams) == 4)
    four card layout
@else

@endif