<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>