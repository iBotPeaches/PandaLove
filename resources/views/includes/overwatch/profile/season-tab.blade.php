<?php
/** @var \Onyx\Overwatch\Objects\Stats $season */
?>
<h3 class="ui top blue attached header">
    Season Stats
</h3>
<div class="ui attached segment">
    <div class="row">
        <div class="12u">
            <div class="ui four statistics">
                <div class="statistic">
                    <div class="value">
                        {{ $season->max_comprank }}
                    </div>
                    <div class="label">
                        SR (Max)
                    </div>
                </div>
                <div class="{{ $season->winRateColor() }} statistic">
                    <div class="value">
                        {{ $season->winRate() }}
                    </div>
                    <div class="label">
                        Win Rate
                    </div>
                </div>
                <div class="statistic">
                    <div class="value">
                        {{ $season->games_played }}
                    </div>
                    <div class="label">
                        Games
                    </div>
                </div>
                <div class="statistic">
                    <div class="value">
                        {{ $season->medals }}
                    </div>
                    <div class="label">
                        Medals
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h3 class="ui top attached header">
    Character Stats
</h3>
<div class="ui attached segment">
    <div class="">
        <?php foreach ($season->characters as $character): ?>
            <div class="row">
                <div class="3u">
                    <div class="ui raised card">
                        <div class="content">
                            <div class="header"><?= $character->character; ?></div>
                            <div class="meta">
                                <span class="category"><?= $character->playtimeFancy(); ?></span>
                            </div>
                        </div>
                        <div class="extra content">
                            <div class="right floated author">
                                <img class="ui avatar image" src="<?= $character->image(); ?>"/>
                            </div>
                        </div>
                        <div class="ui bottom blue attached progress" data-value="<?= intval($character->playtime); ?>" data-total="<?= intval($season->highestPlaytime()); ?>">
                            <div class="bar"></div>
                        </div>
                    </div>
                </div>
                <div class="9u">
                    <div class="ui segment">
                        <div class="ui four statistics">
                            <div class="{{ $character->winRateColor() }} statistic">
                                <div class="value">
                                    {{ $character->winRate() }}
                                </div>
                                <div class="label">
                                    Win Rate
                                </div>
                            </div>
                            <div class="{{ $character->kdColor() }}  statistic">
                                <div class="value">
                                    {{ $character->kd() }}
                                </div>
                                <div class="label">
                                    KD
                                </div>
                            </div>
                            <div class="statistic">
                                <div class="value">
                                    {{ $character->g('general_stats.games_played') }}
                                </div>
                                <div class="label">
                                    Games
                                </div>
                            </div>
                            <div class="statistic">
                                <div class="value">
                                    {{ $character->g('general_stats.medals') }}
                                </div>
                                <div class="label">
                                    Medals
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.ui.bottom.progress').progress();
        });
    </script>
@append