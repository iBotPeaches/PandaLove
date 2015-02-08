<table class="ui table">
    <thead>
        <tr>
            <th>Gamertag</th>
            <th>Grimoire</th>
            <th>Character 1</th>
            <th>Character 2</th>
            <th>Character 3</th>
        </tr>
    </thead>
    <tbody>
        @foreach($members as $member)
            <tr>
                <td><a href="{{ URL::action('ProfileController@index', array($member->seo)) }}">{{ $member->gamertag }}</a></td>
                <td>{{ $member->grimoire }}</td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(1)->background->extra}}" />
                        {{ $member->characterAtPosition(1)->name() }}
                    </span>
                </td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(2)->background->extra}}" />
                        {{ $member->characterAtPosition(2)->name() }}
                    </span>
                </td>
                <td>
                    <span class="right floated author">
                        <img class="ui avatar image" src="{{ $member->characterAtPosition(3)->background->extra}}" />
                        {{ $member->characterAtPosition(3)->name() }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="ui pagination menu">
    {!! with(new Onyx\Laravel\SemanticPresenter($members))->render() !!}
</div>