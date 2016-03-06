<?php
/** @var $accounts \Onyx\Account[] */
?>
<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Gamertag</th>
        <th>Spartan Rank</th>
        <th>isPanda</th>
        <th>Added</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($accounts as $account)
        <tr>
            <td><a target="_blank" href="{{ action('Halo5\ProfileController@index', [$account->seo]) }}">{{ $account->gamertag }}</a></td>
            <td>{{ $account->h5->spartanRank }}</td>
            <td>{{ isset($account->user) ? $account->user->isPandaText() : 'No' }}</td>
            <td>{{ $account->created_at }}</td>
            <td>
                @if (isset($account->user) && $account->user->isPanda)
                    <a href="#" class="ui mini disabled green button">Is Panda</a>
                @else
                    <a href="{{ action('Backstage\IndexController@getSetPanda', [$account->id]) }}" class="ui mini blue button">Make Panda</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($accounts))->render() !!}