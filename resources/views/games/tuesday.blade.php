@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <header>
                    <h1>Welcome to <strong>Raid Tuesday {{ $raidTuesday }}</strong></h1>
                </header>
                <div class="12u">
                    <i>In Progress....</i>
                    <?= dd($combined); ?>
                </div>
            </div>
        </article>
    </div>
@endsection
