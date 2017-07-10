@extends('app')

@section('content')
    <div class="wrapper style1">
        <article class="container no-image" id="top">
            <div class="row">
                <div class="12u">
                    <div class="ui dropdown button" style="overflow: inherit;">
                        <span class="text">Choose {{ $hero->character }} Stat Category</span>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            @foreach ($hero->data as $category => $items)
                                <div class="item">
                                    <i class="dropdown icon"></i>
                                    <span class="text">{{ Onyx\Overwatch\Helpers\String\Text::label($category) }}</span>
                                    <div class="menu">
                                        @foreach($items as $key => $item)
                                            <div class="item">{{ Onyx\Overwatch\Helpers\String\Text::label($key) }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection

@section('inline-js')
    <script type="text/javascript">
        $(function () {
            $('.ui.dropdown')
                .dropdown({
                    direction: 'downward',
                    allowCategorySelection: false
                })
            ;
        });
    </script>
@append