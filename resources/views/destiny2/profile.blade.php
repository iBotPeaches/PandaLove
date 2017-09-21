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
                        <h1>Hi. I am <strong>{{ $account->gamertag }}</strong> <small>(<?= $account->console(); ?>)</small></h1>
                    </header>
                </div>
            </div>
            <div class="ui yellow message">
                This page is under construction, but API is working.
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
    </script>
@append

@section('inline-css')
    <style type="text/css">
    </style>
@append