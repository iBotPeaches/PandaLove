@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <header>
                        <h1>Meet <strong>Panda Love</strong></h1>
                    </header>
                    @include('includes.overwatch.roster.table')
                </div>
            </div>
        </article>
    </div>
@endsection