@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to our <strong>Records</strong></h1>
                </header>
                <div class="12u">
                    @if (! $raids->isEmpty())
                        @include('includes.games.history-table')
                    @else
                        <div class="ui warning message">
                            <strong>Uh oh</strong>
                            <p>
                                We don't have any games yet for history.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
@endsection