<?php
/** @var Onyx\Fortnite\Objects\Stats $member */
?>
<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Solo / Duo / Squad Wins</th>
        <th>Solo / Duo / Squad Top3</th>
        <th>Solo Matches</th>
        <th>Duo Matches</th>
        <th>Squad Matches</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td><a href="{{ URL::action('Fortnite\ProfileController@index', [$member->epic_id]) }}">{{ $member->account->gamertag }}</a></td>
            <td class="center aligned fnwins-table">
                <?= $member->solo_top1 . ' / ' . $member->duo_top1 . ' / ' . $member->squad_top1; ?>
            </td>
            <td class="center aligned fntop3-table">
                <?= $member->solo_top3 . ' / ' . $member->duo_top3 . ' / ' . $member->squad_top3; ?>
            </td>
            <td class="center aligned fnsolomatches-table">
                <?= $member->solo_matchesplayed; ?>
            </td>
            <td class="center aligned fnduomatches-table">
                <?= $member->duo_matchesplayed; ?>
            </td>
            <td class="center aligned fnsquadmatches-table">
                <?= $member->squad_matchesplayed; ?>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($members))->render() !!}