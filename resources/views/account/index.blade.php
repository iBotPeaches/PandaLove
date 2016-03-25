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
            <div class="ui two column middle aligned very relaxed stackable grid">
                <div class="column">
                    @include('includes.account.add-h5')
                </div>
                <div class="ui vertical divider">Or</div>
                <div class="column">
                    @include('includes.account.add-destiny')
                </div>
            </div>
            <h3 class="ui header">Recently Added Accounts</h3>
            <div class="ui stackable grid">
                <h4 class="ui header">Halo 5</h4>
                @include('includes.account.table-h5')
                <h4 class="ui header">Destiny</h4>
                @include('includes.account.table-destiny')
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