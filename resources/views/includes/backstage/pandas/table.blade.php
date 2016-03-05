<?php
/** @var $user \Onyx\User */
?>
<table class="ui striped compact table">
    <thead class="desktop only">
    <tr>
        <th>Name</th>
        <th>Gamertag</th>
        <th>Joined</th>
        <th>Halo?</th>
        <th>Destiny?</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->account->gamertag }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ isset($user->account->h5) ? 'Yes' : 'No' }}</td>
            <td>{{ isset($user->account->destiny) ? 'Yes' : 'No' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! with(new Onyx\Laravel\SemanticPresenter($users))->render() !!}