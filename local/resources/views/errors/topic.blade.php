<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="img/favicon.ico">
    <!-- webfonts -->
    {!! HTML::style('https://fonts.googleapis.com/css?family=Muli:400,300') !!}
    <!-- stylesheets -->
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/app.css') !!}
    <!-- scripts -->
    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
	    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-71362622-1', 'auto');
          ga('send', 'pageview');

        </script>
		<header class="green">
            <div class="row large">
				<div class="small-3 large-2 columns">
					<h1>{!! HTML::link('/','bMOOC') !!}</h1>
				</div>
				<div class="small-12 medium-9 large-3 columns">
					@if (isset($user) && $user->role=="editor")
                        <button class="big plus pullup" data-reveal-id="new_topic">Start a new topic</button>
                    @endif
				</div>
				<div class="large-7 columns">
                </div>
            </div>
        </header>
        <div class="columns small-12">
            <p>There is an error creating the topic: {{ $error->getMessage() }}</p>
            <p>Please <a href="#" class="emphasis" onclick="window.history.back()">go back</a> to correct the error and try to submit the form again</p>
        </div>
    </body>
</html>
