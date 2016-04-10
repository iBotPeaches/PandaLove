@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <h1 class="ui header">
                        {{ $match->playlist->name }} on {{ $match->map->name }}
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="3u">
                    <div class="ui fluid card">
                        <div class="image">
                            <img src="{{ $match->map->getImage() }}" />
                        </div>
                        <div class="content">
                            <div class="left floated author">
                                <img class="ui avatar image" src="{{ $match->gametype->getImage()}}" />
                            </div>
                            <div class="right floated">
                                <span class="header">{{ $match->gametype->name }} on {{ $match->map->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="9u">
                    <div class="ui stackable container menu">
                        <a class="active item" data-tab="overview">
                            Overview
                        </a>
                        @foreach ($match->teams as $team)
                            <a class="item" data-tab="team{{ $team->team_id }}">
                                {{ $team->team->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="ui bottom attached active tab" data-tab="overview">
                        @include('includes.halo5.game.overview-tab')
                    </div>
                    @foreach ($match->teams as $team)
                        <div class="ui bottom attached tab" data-tab="team{{ $team->team_id }}">
                            @include('includes.halo5.game.team-table-tab', ['players' => $match->playersOnTeam($team->key), 'team' => $team])
                        </div>
                    @endforeach
                </div>
            </div>
            @foreach ($match->players as $player)
                @include('includes.halo5.game.modal.advanced', ['player' => $player])
            @endforeach
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item').tab();

            $(".ui.sortable.table").tablesort();

            $('.ui .arena-popup').popup();

            $('.adv-modal-icon').popup();

            $("table").on('click', '.adv-modal-icon', function(event) {
                var key = "." + $(this).data("tag");

                $(key).modal('show');

                $('.medal')
                        .popup({
                            inline   : false,
                            hoverable: true
                        })
                ;
            });
        });
    </script>
@append

@section('inline-css')
    <style type="text/css">
        .adv-modal-icon {
            cursor: pointer;
        }
        .strikethrough-css {
            text-decoration: line-through;
        }
    </style>
@append