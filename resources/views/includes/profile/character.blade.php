<div class="row">
    <div class="4u">
        <div class="ui cards">
            <div class="card">
                <div class="image">
                    <img src="{{ $char->subclass->extraSecondary }}" />
                </div>
                <div class="content">
                    <div class="header">{{ $char->class->title }} <small>({{ $char->level }})</small></div>
                    <div class="meta">
                        {{ $char->gender->title }} {{ $char->race->title }}
                    </div>
                    <div class="description">
                        {{ $char->subclass->description }}
                    </div>
                </div>
                <div class="extra content">
                    <span>
                        <i class="history icon"></i>
                        {{ $char->minutes_played }}
                    </span>
                </div>
            </div>
        </div>
        <img class="ui avatar image" src="{{ $char->emblem->extra }}" />
    </div>
    <div class="8u">

    </div>
</div>
