<?php
/** @var \Onyx\Account[] $member */
?>
<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th class="ui center aligned">Level</th>
        <th class="ui center aligned">Highest SR</th>
        <th class="ui center aligned">Win Rate</th>
        <th class="ui center aligned">Games</th>
        <th class="ui center aligned">Medals</th>
        <th class="ui center aligned">Eliminations</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td>
                <span class="right floated author">
                    <img class="ui avatar image" src="{{ $member->overwatch->avatar }}"/>
                        <a href="{{ URL::action('Overwatch\ProfileController@index', [$member->seo, $member->accountType]) }}">{{ $member->gamertag }}</a>
                </span>
            </td>
            <td class="level-table center aligned">{{ $member->overwatch->totalLevel() }}</td>
            <td class="highest-sr-table center aligned">{{ $member->overwatch->max_comprank }}</td>
            <td class="center aligned {{ $member->overwatch->win_rate >= 50 ? "positive" : "warning" }} win-rate-table">{{ $member->overwatch->win_rate }}</td>
            <td class="center aligned games-table">{{ $member->overwatch->games }}</td>
            <td class="center aligned medals-table">{{ $member->overwatch->medals }}</td>
            <td class="center aligned eliminations-table">{{ $member->overwatch->eliminations }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($members))->render() !!}