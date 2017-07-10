<div class="ui black fluid dropdown button" style="overflow: inherit;">
    <span class="text">Choose Stat Category</span>
    <i class="dropdown icon"></i>
    <div class="menu">
        @foreach ($hero['data'] as $category => $items)
            <div class="item">
                <i class="dropdown icon"></i>
                <span class="text">{{ Onyx\Overwatch\Helpers\String\Text::label($category) }}</span>
                <div class="menu">
                    @foreach($items as $key => $item)
                        <div data-category="{{ $category }}" data-value="{{ $key }}" class="item">{{ Onyx\Overwatch\Helpers\String\Text::label($key) }}</div>
                    @endforeach
                </div>
            </div>
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
                    allowCategorySelection: false,
                    onChange: function(value, text, $choice) {
                        window.location.href = $baseUrl + $choice.data('category') + '/' + $choice.data('value');
                    }
                })
            ;
        });
    </script>
@append