@if (($message = Session::get('flash_message', $flash_message ?? $message ?? null)) !== null)
    <div class="ui {{ $message['type'] or 'green' }} message top-message">
        @if (isset($message['close']) && $message['close'])
            <i class="close icon"></i>
        @endif

        @if (isset($message['header']))
            <div class="header">
                {{ $message['header'] or '' }}
            </div>
        @endif

        {!! $message['body'] or '' !!}
    </div>
@endif

@section('inline-js')
    <script type="text/javascript">
        $('.message .close').on('click', function() {
            $(this).closest('.message').fadeOut();
        });
    </script>
@append