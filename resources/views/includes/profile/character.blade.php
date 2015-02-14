<div class="row">
    <div class="4u">
        <div class="ui cards">
            <div class="card">
                <div class="image">
                    <img src="{{ $char->subclass->extraSecondary }}" />
                </div>
                <div class="content">
                    <div class="header">
                        <img class="ui tiny avatar image" src="{{ $char->emblem->extra }}" />
                        {{ $char->class->title }} <small>({{ $char->level }})</small>
                    </div>
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
    </div>
    <div class="4u">
        <h3 class="ui horizontal header divider">
            Armor
        </h3>
        <div class="ui orange segment tooltips">
            <div class="ui list">
                @foreach($char->armor() as $obj)
                    <div class="item tool" data-html="<strong>{{ $obj->title }}</strong><br />{{ $obj->description }}">
                        <img class="ui avatar image" src="{{ $obj->extra }}" />
                        <div class="content">
                            <a class="header">{{ $obj->title }}</a>
                            <div class="description">
                                {{ \Illuminate\Support\Str::limit($obj->description, 25) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="4u">
        <h3 class="ui horizontal header divider">
            Weapons
        </h3>
        <div class="ui blue segment tooltips">
            <div class="ui list">
                @foreach($char->weapons() as $obj)
                    <div class="item tool" data-html="<strong>{{ $obj->title }}</strong><br />{{ $obj->description }}">
                        <img class="ui avatar image" src="{{ $obj->extra }}" />
                        <div class="content">
                            <a class="header">{{ $obj->title }}</a>
                            <div class="description">
                                {{ \Illuminate\Support\Str::limit($obj->description, 30) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="8u">
        <h3 class="ui horizontal header divider">
            Other
        </h3>
        <div class="ui horizontal list">
            @foreach($char->other() as $obj)
                <div class="item tool" data-position="bottom center" data-html="<strong>{{ $obj->title }}</strong><br />{{ $obj->description }}">
                    <img class="ui avatar image" src="{{ $obj->extra }}" />
                    <div class="content">
                        <a class="header">{{ $obj->title }}</a>
                    </div>
                </div>
            @endforeach
        </div>
        <h3 class="ui horizontal header divider">
            Progression
        </h3>
        @define $light = \Onyx\Destiny\Enums\LightLevels::percentageToNextLevel($char)
        <div class="ui teal progress" data-value="{{ $light['light'] }}" data-total="{{ $light['max'] }}">
            <div class="bar">
                <div class="progress"></div>
            </div>
            <div class="label">{{ $light['message'] }}</div>
        </div>
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $(".item.tool").popup({
                inline: false,
                position: 'top center'
            });

            $(".ui.progress").progress({
                label: 'ratio',
                text: {
                    ratio: '{value}/{total} light'
                }
            });
        });
    </script>
@append