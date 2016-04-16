@extends('app')

@section('content')
    <div class="wrapper style1">
        <article id="">
            <header>
                <h2>Uh Oh</h2>
            </header>
            <div class="ui red message">
                <p>
                    {{ $message or 'We had an error. Sorry.' }}
                </p>
            </div>
        </article>
    </div>
@endsection