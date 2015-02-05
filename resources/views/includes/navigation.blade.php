<nav id="nav">
    <ul class="container">
        <li><a class="active" href="#top">Home</a></li>
        <li><a href="#portfolio">Roster</a></li>
        <li><a href="#contact">Games</a></li>
        @if ($user != null)
            <li><a href="">Control Panel</a></li>
            <li><a href="{{ URL::action('AuthController@getLogout') }}">Logout</a></li>
        @else
            <li><a href="{{ URL::action('AuthController@getLogin') }}">Sign In</a></li>
        @endif
    </ul>
</nav>