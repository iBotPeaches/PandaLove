@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to our <strong>Game History</strong></h1>
                </header>
                <div class="9u">
                    <h3 class="ui horizontal header divider">
                        Raids
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $raids])
                    <h3 class="ui horizontal header divider">
                        Prison of Elders
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $poe])
                    <h3 class="ui horizontal header divider">
                        Trials of Osiris
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $passages])
                    <h3 class="ui horizontal header divider">
                        Raid Tuesdays
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $tuesday])
                    <h3 class="ui horizontal header divider">
                        Flawless Raids
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $flawless])
                    <h3 class="ui horizontal header divider">
                        PVP
                    </h3>
                    @include('includes.destiny.games.game-list', ['games' => $pvp])
                </div>
                <div class="3u">
                    <h3 class="ui horizontal header divider">
                        Complete History
                    </h3>
                    <div class="ui orange segment">
                        <div class="ui selection list">
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['Raid']) }}">
                                <div class="content">
                                    Raids
                                </div>
                            </a>
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['PoE']) }}">
                                <div class="content">
                                    Prison of Elders
                                </div>
                            </a>
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['ToO']) }}">
                                <div class="content">
                                    Trials of Osiris
                                </div>
                            </a>
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['RaidTuesdays']) }}">
                                <div class="content">
                                    Raid Tuesdays
                                </div>
                            </a>
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['Flawless']) }}">
                                <div class="content">
                                    Flawless Raids
                                </div>
                            </a>
                            <a class="item no_underline" href="{{ URL::action('Destiny\GameController@getHistory', ['PVP']) }}">
                                <div class="content">
                                    PVP
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
    <style type="text/css">
        .non-white-bg {
            background: -webkit-gradient(linear, 50% 0%, 50% 100, color-stop(100%, rgba(0, 0, 0, 0)), color-stop(100%, #000));
            background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0) 100px, #000 100px);
            background: -moz-linear-gradient(top, rgba(0, 0, 0, 0) 100px, #000 100px);
            background: -o-linear-gradient(top, rgba(0, 0, 0, 0) 100px, #000 100px);
            background: linear-gradient(top, rgba(0, 0, 0, 0) 100px, #000 100px);
        }
        .no_underline {
            text-decoration: none;
        }
    </style>
@append