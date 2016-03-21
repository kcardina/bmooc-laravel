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
        <div class="columns medium-6 large-3 small-centered">
            <h2>Sign in</h2>
              <p>Using bMOOC for the first time? {!! HTML::link('auth/register','Create an account', ['class'=>'emphasis', 'data-reveal-id'=>'signup', 'data-reveal-ajax'=>'true']) !!}.</p>

               @if (count($errors))
                        @foreach($errors->all() as $error)
                            <small class="error">{{ $error }}</small>
                        @endforeach
                @endif

                {!! Form::open(array('data-abide', 'url'=>'/auth/login','method'=>'POST')) !!}
                   <div>
                    <label>Email:
                        <input type="email" required name="email" value="{{ old('email') }}">
                    </label>
                    <small class="error">Please enter a valid e-mail address.</small>
            </div>
                   <div>
                    <label>Password:
                        <input type="password" required name="password" id="password">
                    </label>
                    <small class="error">Please enter your password.</small>
            </div>

                    <label>Remember me:
                        <input type="checkbox" name="remember">
                    </label>

                    <input type="submit" class="full purple" value="Login" />
                {!! Form::close() !!}
                <p><small>Trouble signing in? {!! HTML::link('#', 'Send us a message', array('class'=>'emphasis', 'data-reveal-id' => 'feedback')) !!}.</small></p>

        </div>

        {!! HTML::script('js/vendor/jquery.js') !!}
        {!! HTML::script('js/foundation.min.js') !!}
        {!! HTML::script('js/help.js') !!}
        {!! HTML::script('js/app.js') !!}
    </body>
</html>
