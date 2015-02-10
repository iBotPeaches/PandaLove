@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to our <strong>Game History</strong></h1>
                </header>
                <div class="9u">
                    <h4 class="ui horizontal header divider">
                        Raids
                    </h4>
                    <div class="ui divided list">
                        @foreach($raids as $raid)
                            <div class="item">
                                <div class="right floated compact ui button">View</div>
                                <img class="ui avatar bordered image non-white-bg" src="{{ $raid->type()->extra }}" />
                                <div class="content">
                                    <div class="header">
                                        {{ $raid->type()->title }}
                                        @if ($raid->isHard)
                                            <span class="ui horizontal red label">Hard</span>
                                        @else
                                            <span class="ui horizontal green label">Normal</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h4 class="ui horizontal header divider">
                        Raid Tuesdays
                    </h4>
                    <h4 class="ui horizontal header divider">
                        Flawless Raid
                    </h4>
                </div>
                <div class="3u">

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
    </style>
@append