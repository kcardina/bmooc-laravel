<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC LUCA School of Arts</title>
		<link rel="icon" type="img/ico" href="img/favicon.ico">
    {!! HTML::style('https://fonts.googleapis.com/css?family=Questrial') !!}
    {!! HTML::style('https://fonts.googleapis.com/css?family=Open+Sans:400,700') !!}
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/app.css') !!}

    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
		<header class="green">
			<div class="row">
				<div class="small-6 medium-3 large-2 columns">
					<h1>{!! HTML::link('/','bMOOC') !!}</h1>
				</div>
				<div class="small-6 medium-2 medium-push-7 large-push-9 large-1 columns text-right">
					@if (isset($user))
						{!! HTML::link('logout','Sign out', array('class'=>'logout')) !!}
					@else
						{!! HTML::link('login/twitter','Sign in with Twitter', ['class'=>'logout']) !!}
					@endif
				</div>
				<div class="medium-7 medium-pull-2 large-pull-1 large-3 columns">
				</div>
				<div class="large-6 large-pull-1 columns">
        </div>
      </div>
    </header>
    
    <div class="items" style="margin-left: 40px; margin-right: 40px;">
    <h3>bMOOC</h3>
		<p>bMOOC exists out of topics. A topic is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p>
		<p>What joins the topic, is not fixed. The topic can change its course at all times. The word "topic" derives from the Greek ta topica, which means "commonplace". The topic offers a common place of attention for (some)thing(s), a place for forms of (re)searching that may lead eventually to an artistic practice.</p>
		<p>A topic is presented by juxtapositions of images/artefacts/things. In other words, it's the relations, commonalities or positions of these things that matter. What these are is often unclear, ambiguous or polysemic.</p>
		<h3>Navigation</h3>
		<p>Navigate a topic by moving the images/artefacts/things. Intervene, explore, trouble, clarify or contribute to a topic by adding (some)thing. What you can add, depends on the topic. This could be an audio recording, a piece of text or a mystery. Push "add (some)thing" wherever you want to add/intervene/contribute, and then follow the instructions of the topic.
		</p>
        
    </div>
    
    
    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/foundation/foundation.equalizer.js') !!}
    <script>
		$(document).foundation();
		</script>
  </body>
</html>
