@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container" id="top">
            <div class="row">
                <div class="12u">
                    @include('includes.backstage.menu')
                    <br />
                    <h3 class="ui header">Current Destiny Accounts</h3>
                    @if (count($accounts) > 0)
                        @include('includes.backstage.destiny.table')
                    @else
                        <div class="ui warning message">
                            I've detected 0 Destiny accounts. This is not right.
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
    @include('includes.backstage.destiny.add-game')
@endsection