<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="img/favicon.ico">

    {{-- FONTS --}}
    {!! HTML::style('https://fonts.googleapis.com/css?family=Muli:400,300') !!}

    {{-- STYLESHEETS --}}
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/app.css?v=' . Version::get()) !!}

    {{-- SCRIPTS --}}
    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
        {{-- JS: Google Analytics --}}
	    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-71362622-1', 'auto');
          ga('send', 'pageview');
        </script>

        {{-- CSS: Font Awesome --}}
        <script type="text/javascript">
            (function() {
                var css = document.createElement('link');
                css.href = '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css';
                css.rel = 'stylesheet';
                css.type = 'text/css';
                document.getElementsByTagName('head')[0].appendChild(css);
            })();
        </script>
        {{-- CSS: QuilJS --}}
        <script type="text/javascript">
            (function() {
                var css = document.createElement('link');
                css.href = '//cdn.quilljs.com/0.20.1/quill.snow.css';
                css.rel = 'stylesheet';
                css.type = 'text/css';
                document.getElementsByTagName('head')[0].appendChild(css);
            })();
        </script>
    </body>
</html>
