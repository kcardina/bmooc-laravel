<!doctype html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8">
		<title>bMOOC-topic</title>
		{!! HTML::script('js/vendor/modernizr.js') !!}
		{!! HTML::script('//code.jquery.com/jquery-1.11.3.min.js') !!}
		{!! HTML::script('//code.jquery.com/jquery-migrate-1.2.1.min.js') !!}
		{!! HTML::script('//code.jquery.com/ui/1.11.4/jquery-ui.js') !!}
		{!! HTML::script('js/jquery.validate.min.js') !!}
		{!! HTML::script('js/additional-methods.js') !!}
		{!! HTML::script('js/foundation/foundation.js') !!}
		{!! HTML::script('js/foundation/foundation.reveal.js') !!}
				
		{!! HTML::style('css/foundation.css') !!}
		{!! HTML::style('css/portfolio-theme.css') !!}
		{!! HTML::style('css/topic.css') !!}
	</head>
	<body>
		<!--  Heading with menu -->
		<div class="row">
			<div class="small-12 medium-4 large-6 columns namelogo">
				<h1>bMOOC-topic <span id="topic_title"></span></h1>
			</div>
			<div class="small-12 medium-8 large-6 columns">
				<div class="nav-bar">
					<ul class="button-group">
						<li>{!! isset($user)? 'Welkom '.$user->username: HTML::link('login/twitter','Aanmelden met Twitter', array('class'=>'button'))!!}</li>
						<li>{!! HTML::link('/', 'Home', array('class'=>'button')) !!}</li>
						<li>{!! isset($user)? HTML::link('logout','Afmelden', array('class'=>'button')): '' !!}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Heading with menu -->
		
		<!-- Included by sub template to show a topic -->
		@yield('topic_body')
		<!-- End Included by sub template to show a topic -->
		
		<!-- Footer -->
		<footer class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="row">
					<div class="large-6 columns">
						<p>&copy; Kris Cardinaels - LUCA School of Arts, 2015.</p>
					</div>
					<div class="large-6 columns">
						<ul class="inline-list right">
							<li><a href="#">Over bMOOC</a></li>
							<li>{!! HTML::link('help', 'Help') !!}</li>
							<li><a href="#">Privacy</a></li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<!-- End Footer -->
		
		@yield('topic_script')
	</body>
</html>