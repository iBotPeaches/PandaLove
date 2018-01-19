<?php
/** @var /Onyx/Account $account */
?>
@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong>
                            <small>({{ $account->console() }})</small>
                        </h1>
                    </header>
                    <div class="ui stackable menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        <a class="item" data-tab="solo">
                            Solo
                        </a>
                        <a class="item" data-tab="duo">
                            Duo
                        </a>
                        <a class="item" data-tab="squad">
                            Squad
                        </a>
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.fortnite.profile.tabs.overview')
                    </div>
                    <div class="ui bottom attached tab" data-tab="solo">
                        @include('includes.fortnite.profile.tabs.solo')
                    </div>
                    <div class="ui bottom attached tab" data-tab="duo">
                        @include('includes.fortnite.profile.tabs.duo')
                    </div>
                    <div class="ui bottom attached tab" data-tab="squad">
                        @include('includes.fortnite.profile.tabs.squad')
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $('.menu .item').tab();

        $(function() {
            $.ajax({
                url: '{{ URL::action('Fortnite\ProfileController@checkForUpdate', [$stats->epic_id]) }}',
                success: function(result) {
                    $msg = $("#update-message");
                    if (result.updated && result.frozen == false) {
                        $msg.removeClass('icon').addClass('green');
                        $("#update-message .content p").empty().text("Account Updated! Refresh for new data");
                    } else if (result.updated == false && result.frozen == false) {
                        $msg.removeClass('icon').addClass('blue');
                        $("#update-message .content p").empty().text("Account last updated: " + result.last_update);
                    } else if (result.frozen) {
                        $msg.removeClass('icon').addClass('yellow');
                        $("#update-message .content p").empty().html(result.last_update);
                    }

                    $("#update-message i").remove();
                    $("#update-message .header").remove();
                }
            });
        })
    </script>
@append

@section('inline-css')
    <style type="text/css">
        .no_underline {
            text-decoration: none;
        }
    </style>
@append