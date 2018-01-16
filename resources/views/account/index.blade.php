@extends('app')

@section('content')
    <div class="wrapper style1 first">
        <article class="container">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Add your Gamertag</h1>
                    </header>
                </div>
            </div>
            <div class="ui divider"></div>
            <div class="ui two column very relaxed stackable grid">
                <div class="column">
                    @include('includes.account.add-destiny')
                </div>
                <div class="column">
                    @include('includes.account.add-destiny2')
                </div>
            </div>
            <div class="ui divider"></div>
            <div class="ui two column very relaxed stackable grid">
                <div class="column">
                    @include('includes.account.add-h5')
                </div>
                <div class="column">
                    @include('includes.account.add-overwatch')
                </div>
            </div>
            <div class="ui divider"></div>
            <div class="ui two column very relaxed stackable grid">
                <div class="column">
                    @include('includes.account.add-fortnite')
                </div>
                <div class="column">
                    <div class="ui info message">
                        TODO: Another game
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection