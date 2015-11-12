@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong></h1>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="3u">
                    <div class="ui fluid card">
                        <div class="image">
                            <img src="{{ $account->h5->getSpartan() }}" />
                        </div>
                        <div class="content">
                            <div class="left floated author">
                                <img class="ui avatar image" src="{{ $account->h5->getEmblem() }}" />
                            </div>
                            <div class="right floated">
                                <span class="header">Level {{ $account->h5->spartanRank }} Spartan</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="9u">
                    <div class="ui top menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        <a class="item" data-tab="playlists">
                            Playlists
                        </a>
                        <a class="item" data-tab="medals">
                            Medals
                        </a>
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.halo5.profile.overview-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="playlists">
                        @include('includes.halo5.profile.playlists-tab')
                    </div>
                    <div class="ui bottom attached tab" data-tab="medals">
                        @include('includes.halo5.profile.medals-tab')
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();

            $('.medal')
                    .popup({
                        inline   : false,
                        hoverable: true
                    })
            ;
        });
    </script>
@append

@section('inline-css')
    <style type="text/css">
        .stat-fix {
            margin-bottom: -13px;
        }
    </style>
@append