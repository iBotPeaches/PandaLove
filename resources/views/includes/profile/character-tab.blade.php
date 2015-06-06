<div class="ui top attached tabular menu">
    <a class="{{ $characterId == false ? 'active' : null }} item" data-tab="overview">Overview</a>
    @foreach($account->characters as $char)
        <a class="{{ $characterId == $char->characterId ? 'active' : null }} item" data-tab="char_{{ $char->characterId }}">{{ $char->level }} {{$char->class->title}}</a>
    @endforeach
    @if (count($games) > 0)
        <a class="item" data-tab="unbroken-games">Unbroken Games</a>
    @endif
</div>
<div class="ui bottom attached {{ $characterId == false ? 'active' : null }} tab segment" data-tab="overview">
    @include('includes.profile.overview-tab')
</div>
@foreach($account->characters as $char)
    <div class="ui bottom attached {{ $characterId == $char->characterId ? 'active' : null }} tab segment" data-tab="char_{{ $char->characterId }}">
        @include('includes.profile.character', ['char' => $char])
    </div>
@endforeach
@include('includes.profile.unbroken-tab')

@section('inline-js')
    <script type="text/javascript">
        $(function() {
            $('.menu .item')
                    .tab()
            ;
        });
    </script>
@append