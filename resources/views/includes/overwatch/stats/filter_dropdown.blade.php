<div class="ui black fluid pointing scrolling dropdown button" style="overflow: inherit;">
    <span class="text">Choose Stat Category</span>
    <i class="dropdown icon"></i>
    <div class="menu">
        <div class="ui icon search input">
            <i class="search icon"></i>
            <input type="text" placeholder="Search tags...">
        </div>
        <div class="divider"></div>
        @foreach ($hero['data'] as $category => $items)
            <div class="header">
                <i class="tags icon"></i>
                {{ Onyx\Overwatch\Helpers\String\Text::label($category) }}
            </div>
            <div class="divider"></div>
            @foreach($items as $key => $item)
                <div data-category="{{ $category }}" data-value="{{ $key }}" class="item">{{ Onyx\Overwatch\Helpers\String\Text::label($key) }}</div>
            @endforeach
        @endforeach
    </div>
</div>

@section('inline-js')
    <script type="text/javascript">
        $(function () {
            var $baseUrl = '{{ action('Overwatch\StatsController@getCharacter', [strtolower($hero['character'])]) }}/';

            $('.ui.dropdown')
                .dropdown({
                    direction: 'downward',
                    fullTextSearch: 'exact',
                    allowCategorySelection: false,
                    onChange: function(value, text, $choice) {
                        window.location.href = $baseUrl + $choice.data('category') + '/' + $choice.data('value');
                    }
                })
            ;
        });
    </script>
@append