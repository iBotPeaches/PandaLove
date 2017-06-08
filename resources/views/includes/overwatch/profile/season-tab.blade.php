<?php
/** @var \Onyx\Overwatch\Objects\Stats $season */
?>
<h3 class="ui top attached header">
    Character Playtime
</h3>
<div class="ui attached segment">
    <div class="row">
        <div class="12u">
            <?php foreach ($season->characters as $character): ?>
                <li class="ui image label">
                    <img src="<?= $character->image(); ?>" />
                    <?= $character->character; ?> (<?= $character->playtimeFancy(); ?>)
                </li>
            <?php endforeach; ?>
        </div>
    </div>
</div>

@section('inline-js')
@append