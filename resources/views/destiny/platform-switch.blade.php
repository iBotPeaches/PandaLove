@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Hi. Pick the right <strong>Platform</strong></h1>
                    </header>
                    <div class="ui cards">
                        <div class="card">
                            <div class="content">
                                <img class="right floated mini ui image" src="{{ $accounts[0]->console_image() }}">
                                <div class="header">
                                    {{ $accounts[0]->gamertag }}
                                </div>
                                <div class="meta">
                                    {{ $accounts[0]->console() }}
                                </div>
                                <div class="description">
                                    @include('includes.account.add-blank-destiny', ['data' => $accounts[0]])
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="content">
                                <img class="right floated mini ui image" src="{{ $accounts[1]->console_image() }}">
                                <div class="header">
                                    {{ $accounts[1]->gamertag }}
                                </div>
                                <div class="meta">
                                    {{ $accounts[1]->console() }}
                                </div>
                                <div class="description">
                                    @include('includes.account.add-blank-destiny', ['data' => $accounts[1]])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-css')
    <style type="text/css">
        .vertical.divider {
            height: 5rem !important;
        }
    </style>
@append