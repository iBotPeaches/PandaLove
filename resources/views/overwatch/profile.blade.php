<?php
/**
 * @var \Onyx\Account $account
 * @var \Onyx\Overwatch\Objects\Character $main
 * @var \Onyx\Overwatch\Objects\Stats $overall
 */
?>
@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong>&nbsp;<small>({{ $account->console() }})</small></h1>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="3u">
                    <div class="ui fluid card">
                        <div class="image">
                            <img src="{{ $main->image() }}" />
                        </div>
                        <div class="content">
                            <div class="left floated author">
                                <img class="ui avatar image" src="{{ $overall->avatar }}" />
                            </div>
                            <div class="right floated">
                                <span class="header">Level {{ $overall->totalLevel() }} ({{ $overall->tier }})</span>
                            </div>
                        </div>
                    </div>
                    @if (isset($user) && $user != null)
                        <div class="ui raised segment" id="manual-refresh-button" style="display:none;">
                            <a href="{{ action('Overwatch\ProfileController@manualUpdate', [$account->seo, $account->accountType]) }}" class="ui fluid green button">Refresh Data</a>
                        </div>
                    @endif
                </div>
                <div class="9u">
                    <div class="ui stackable container menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        @foreach ($account->overwatch as $overwatch)
                            <a class="item" data-tab="season-{{ $overwatch->season }}">
                                Season {{ $overwatch->season }}
                            </a>
                        @endforeach
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.overwatch.profile.overview-tab')
                    </div>
                    @foreach ($account->overwatch as $overwatch)
                        <div class="ui bottom attached tab" data-tab="season-{{ $overwatch->season }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();
        });
    </script>
@append

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $.ajax({
                url: '{{ URL::action('Overwatch\ProfileController@checkForUpdate', [$account->gamertag, $account->accountType]) }}',
                success: function(result) {
                    $msg = $("#update-message");
                    if (result.updated && result.frozen == false) {
                        $msg.removeClass('icon').addClass('green');
                        $("#update-message .content p").empty().text("Account Updated! Refresh for new data");
                    } else if (result.updated == false && result.frozen == false) {
                        $msg.removeClass('icon').addClass('blue');
                        $("#update-message .content p").empty().text("Account last updated: " + result.last_update);
                        $("#manual-refresh-button").show();
                    } else if (result.frozen) {
                        $msg.removeClass('icon').addClass('yellow');
                        $("#update-message .content p").empty().html(result.last_update);
                    }

                    $("#update-message i").remove();
                    $("#update-message .header").remove();
                }
            });
        });
    </script>
@append

@section('inline-css')

@append