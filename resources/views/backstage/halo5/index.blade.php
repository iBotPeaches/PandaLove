@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container" id="top">
            <div class="row">
                <div class="12u">
                    @include('includes.backstage.menu')
                    <br />
                    <h3 class="ui header">Current Halo 5 Accounts</h3>
                    @if (count($accounts) > 0)
                        @include('includes.backstage.halo5.table')
                    @else
                        <div class="ui warning message">
                            I've detected 0 Halo 5 accounts. This is not right.
                        </div>
                    @endif
                    <div class="ui divider"></div>
                    <h3 class="ui header">Current Halo 5 Map Count</h3>
                    <div class="ui celled ordered list">
                        @foreach ($maps as $map)
                            <div class="item">{{ "   " . $map->name . " - " . $map->total . " times played." }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection