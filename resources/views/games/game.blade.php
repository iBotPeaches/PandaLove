@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <?= dd($game); ?>
            </div>
        </article>
    </div>
@endsection