@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container" id="top">
            <div class="row">
                <div class="12u">
                    @include('includes.backstage.menu')
                    <br />
                    <h3 class="ui header">Current Pandas</h3>
                    @if (count($users) > 0)
                        @include('includes.backstage.pandas.table')
                    @else
                        <div class="ui warning message">
                            I've detected 0 pandas. This is not right.
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
@endsection