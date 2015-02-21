<div class="wrapper style4 wrapper-footer">
    <article id="contact" class="container 75%">
        <footer>
            <ul id="copyright">
                <li>Logo by Brewster851</li>
                <li>Programming by <a href="http://twitter.com/iBotPeaches" target="_blank">@iBotPeaches</a></li>
                <li>Design by <a href="http://html5up.net">HTML5UP</a></li>
            </ul>
        </footer>
    </article>
</div>

@section('inline-js')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-3737795-16', 'auto');
        ga('send', 'pageview');

        @if (isset($user) && $user != null)
            ga('set', '&uid', '{{ $user->google_id }}');
        @endif
    </script>
@append