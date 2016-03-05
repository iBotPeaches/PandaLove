<nav id="nav">
    <ul class="container">
        <li><a class="{{ HTML::activeClass('/') }}" href="{{ URL::to('/') }}"><i class="home icon"></i></a></li>
        <li><a class="{{ HTML::activeClass('roster') }}" href="{{ URL::action('HomeController@getRoster') }}">Roster</a></li>
        <li><a class="{{ HTML::activeClass('destiny/games') }}" href="{{ URL::action('Destiny\GameController@getIndex') }}">Games</a></li>
        @if (isset($user) && $user != null)
            @if ($user->isPanda)
                <li><a class="{{ HTML::activeClass('calendar') }}" href="{{ URL::action('CalendarController@getIndex') }}">Calendar</a></li>
            @endif
            <li><a class="{{ HTML::activeClass('usercp') }}" href="{{ URL::action('UserCpController@getIndex') }}"><i class="settings icon"></i></a></li>
            <li><a href="{{ URL::action('UserCpController@getLogout') }}"><i class="sign out icon"></i></a></li>
        @else
            <li><a href="{{ URL::action('AuthController@getLogin') }}"><i class="google plus icon"></i></a></li>
        @endif
    </ul>
</nav>