<div class="ui top attached tabular menu">
    <a class="active item" data-tab="overview">Overview</a>
    @foreach($account->characters as $char)
        <a class="item" data-tab="char_{{ $char->characterId }}">{{ $char->level }} {{$char->class->title}}</a>
    @endforeach
</div>
<div class="ui bottom attached active tab segment" data-tab="overview">
    @include('includes.profile.overview-tab')
</div>
@foreach($account->characters as $char)
    <div class="ui bottom attached tab segment" data-tab="char_{{ $char->characterId }}">
        @include('includes.profile.character', ['char' => $char])
    </div>
@endforeach

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item')
                    .tab()
            ;
        });
    </script>
@append