@foreach ($match->players as $player)
    <span class="spartan-adjust ui label green" data-id="{{ $player->account_id . "_spartan" }}">{{ $player->account->gamertag }}</span>
@endforeach
<div class="ui divider"></div>
<div id="timeline-box">
    <ul class="timeline">
        @foreach ($combined as $time)
            <li class="timeline-item" style="">
                <div class="timeline-badge primary"><a><i class="ui icon bullseye" rel="tooltip" title="{{ $time['stats']['time'] }}"></i></a></div>
                <div class="timeline-panel">
                    <div class="timeline-body">
                        <div class="ui middle aligned divided list">
                            @foreach ($time as $user_id => $items)
                                @if ($user_id !== "stats")
                                    @foreach ($items as $item)
                                        <div class="item {{ $item->killer_id }}_spartan {{ $item->victim_id }}_spartan" style="display: list-item;">
                                            @include('includes.halo5.game.events.types.' . \Onyx\Halo5\Enums\EventName::getSeo($item->event_name), ['event' => $item])
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="timeline-footer">
                        {{ $time['stats']['time'] }} into match.
                    </div>
                </div>
            </li>
        @endforeach
        <li class="clearfix" style="float: none;"></li>
    </ul>
    <div class="ui page dimmer">
        <div class="content">
            <div class="center">
                <h2 class="ui inverted icon header">
                    <i class="lab icon"></i>
                    Updating Timeline
                </h2>
            </div>
        </div>
    </div>
</div>

@section('inline-css')
    <style type="text/css">
        .spartan-adjust {
            cursor: pointer;
        }
    </style>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            var key;
            var $that;

            $('#timeline-box').dimmer("setting", {
                onShow: function() {
                    if ($that.hasClass('green')) {
                        $(key).show();
                        $(key).parents('.timeline-item').show();
                    } else {
                        $(key).hide();
                    }

                    $(".timeline-item").each(function() {
                        if ($(this).find(".item:visible").length === 0) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                    $("#timeline-box").dimmer('hide');
                }
            });

            $(".spartan-adjust").click(function() {
                key = "." + $(this).data("id");
                $that = $(this);

                var addGreen = false;
                if ($that.hasClass('green')) {
                    $that.removeClass('green');
                } else {
                    addGreen = true;
                }

                key += ":not(";
                $(".spartan-adjust.green").each(function() {
                    key += "." + $(this).data("id") + "):not(";
                });
                key += ")";

                if (addGreen) {
                    $that.addClass('green');
                }

                $("#timeline-box").dimmer('show');
            });
        });
    </script>
@append