<nav id="nav">
    <ul class="container">
        <li><a class="{{ HTML::activeClass('/') }}" href="{{ URL::to('/') }}">Home</a></li>
        <li><a class="{{ HTML::activeClass('profile') | HTML::activeClass('roster') }}" href="{{ URL::action('RosterController@getIndex') }}">Roster</a></li>
        <li><a class="{{ HTML::activeClass('games') }}" href="{{ URL::action('GameController@getIndex') }}">Games</a></li>
        @if (isset($user) && $user != null && $user->account->isPandaLove())
            <li><a class="{{ HTML::activeClass('calendar') }}" href="{{ URL::action('CalendarController@getIndex') }}">Calendar</a></li>
            <li><a class="{{ HTML::activeClass('usercp') }}" href="{{ URL::action('UserCpController@getIndex') }}">Control Panel</a></li>
            <li><a href="{{ URL::action('UserCpController@getLogout') }}">Logout</a></li>
        @else
            <li><a href="{{ URL::action('AuthController@getLogin') }}">Sign In</a></li>
        @endif
    </ul>
</nav>