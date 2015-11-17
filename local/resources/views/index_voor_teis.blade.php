<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<title>bMOOC-discussies</title>
	{!! HTML::script('js/vendor/modernizr.js') !!}
	{!! HTML::script('//code.jquery.com/jquery-1.11.3.min.js') !!}
	{!! HTML::script('//code.jquery.com/jquery-migrate-1.2.1.min.js') !!}
	{!! HTML::script('js/foundation/foundation.js') !!}
	{!! HTML::script('js/foundation/foundation.reveal.js') !!}
	{!! HTML::script('//code.jquery.com/ui/1.11.4/jquery-ui.js') !!}
	{!! HTML::script('js/jquery.validate.min.js') !!}
	{!! HTML::script('js/additional-methods.js') !!}
	
	
	{!! HTML::style('css/foundation.css') !!}
	{!! HTML::style('css/portfolio-theme.css') !!}
	{!! HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}
	<link href="http://fonts.googleapis.com/css?family=Raleway:600,400,200" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<style type="text/css">
    	body{font-family:'Raleway',Helvetica,Arial,sans-serif;font-weight:400;background-color:rgba(0,0,0,0.02);}
    	.button{font-family:'Raleway',Helvetica,Arial,sans-serif;}
    	.button-group li{padding-top:3%;}
    	.button-group a{color:rgba(0,0,0,1);background-color:transparent;}
    	.button-group a:hover{color:rgba(0,0,0,0.4);background-color:transparent;}
    	.button-group{float:none;}
		@media only screen and (min-width: 40.063em) {.button-group{float:right;}}.namelogo{padding:20px 10px 0px 10px;}
		@media only screen and (min-width: 40.063em) {.namelogo{padding-bottom:20px;}}.namelogo h1{font-size:1.5em;font-weight:600;text-align:center;}
		@media only screen and (min-width: 40.063em) {.namelogo h1{font-size:16px;text-align:left;}}
		@media only screen and (min-width: 64.063em) {.namelogo h1{font-size:24px;text-align:left;}}.hero{background:url(http://placehold.it/900x800);background-size:cover;box-shadow:inset 0px 0px 0 2000px rgba(0,0,0,0.15);height:80%;}.intro-text{padding-top:40%;color:rgba(255,255,255,1);text-align:center;}
		@media only screen and (min-width: 40.063em) {.intro-text{padding-top:25%;}}
		@media only screen and (min-width: 64.063em) {.intro-text{padding-top:18%;}}.intro-text p{font-family:'Shadows Into Light',cursive;font-size:44px;}.about,.work,.contact{padding:50px 0 0 0;}.about img{width:250px;height:250px;border-radius:150px;-webkit-border-radius:150px;-moz-border-radius:150px;}.work img:hover{opacity:0.5;-webkit-transition:0.3s ease;-moz-transition:0.3s ease;-ms-transition:0.3s ease;-o-transition:0.3s ease;transition:0.3s ease;}.work li{height:140px;overflow:hidden;}hr{border:0;height:1px;background-image:-webkit-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.75),rgba(0,0,0,0));background-image:-moz-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.75),rgba(0,0,0,0));background-image:-ms-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.75),rgba(0,0,0,0));background-image:-o-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.75),rgba(0,0,0,0));opacity:0.8;}footer{padding:80px 0 10px 0;}footer a:hover{color:rgba(0,0,0,0.4);background-color:transparent;}

		.pijl {
			display: block; position: absolute; top: 50%; margin-top: -20px; color: white; font-weight: bold;
		}
		.linksepijl img {
			-ms-transform: rotate(-180deg); /* IE 9 */
			-webkit-transform: rotate(-180deg); /* Safari */
			transform: rotate(-180deg); /* Standard syntax */
   		}
   		.naaronderpijl img {
			-ms-transform: rotate(90deg); /* IE 9 */
			-webkit-transform: rotate(90deg); /* Safari */
			transform: rotate(90deg); /* Standard syntax */
		}
   		.naarbovenpijl img {
			-ms-transform: rotate(-90deg); /* IE 9 */
			-webkit-transform: rotate(-90deg); /* Safari */
			transform: rotate(-90deg); /* Standard syntax */
		}
		
		.ui-front{ z-index:1010; }
	</style>
</head>
<body>
	<div class="row">
		<div class="small-12 medium-4 large-6 columns namelogo">
			<h1>bMOOC-discussies {{ isset($titel)?$titel:"" }}</h1>
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

	<div class="row" style="max-width: 100%;">
	
	<?php 
		$aantalDiscussies = count($discussies);
		$teller = 0;
		foreach ($discussies as $discussie) {
		//for ($teller=0; $teller<$aantalDiscussies; $teller++) {
			//$discussie = $discussies[$teller];
	?>
		<div class="small-2 columns <?php echo ($teller%6==5 || $teller==($aantalDiscussies-1))?'end':''; ?>" style="max-width: 100%; overflow: hidden; object-fit: cover; align: center">
			<p style="font-size: 17px; font-weight: bold;">{!! $discussie->titel !!}</p>
			@if($discussie->type == 'text')
			<p>{!! $discussie->inhoud !!}</p>
			@elseif($discussie->type == 'local_image')
			{!! HTML::image('uploads/'.$discussie->url, '', array('style' => 'max-height: 150px; min-width: 100%; object-fit: cover')) !!}
			@elseif($discussie->type == 'remote_image')
			{!! HTML::image($discussie->url, '', array('style' => 'max-height: 150px; min-width: 100%; object-fit: cover')) !!}
			@elseif($discussie->type == 'local_pdf' || $discussie->type == 'remote_pdf')
			<div id="pdf" style="min-height: 100%; min-width: 100%">Bekijk de discussie voor het pdf-document</div>
			@elseif($discussie->type == 'video_youtube')
			<iframe  src="{{ $discussie->url }}?autoplay=0" width="100%"  height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			@elseif($discussie->type == 'video_vimeo')
			<!-- vimeolink: https://player.vimeo.com/video/7338120 -->
			<iframe src="{{ $discussie->url }}" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			@elseif($discussie->type == 'remote_document')
			{!! HTML::link($discussie->url, 'Bekijk dit document') !!}
			@endif
			<a href="{{ URL::to('discussion') }}/{{ $discussie->id}}_0/0/$">Bekijk</a>
			<div class="row"><div class="small-6 columns small-centered">Antwoorden:<hr />
			<?php
				$d = $discussie;
				while (count($d->antwoorden) > 0) {
					$eersteAntwoord = $d->antwoorden[0];
					echo '<div class="row"><div class="small-12 columns small-centered">';
					switch ($eersteAntwoord->type) {
						case 'text':
							echo $eersteAntwoord->inhoud;
							break;
						case 'local_image':
							echo HTML::image('uploads/'.$eersteAntwoord->url, '');
							break;
						case 'remote_image':
							echo HTML::image($eersteAntwoord->url);
							break;
						case 'local_pdf':
						case 'remote_pdf':
							break;
						case 'video_youtube':
							echo '<iframe  src="'.$eersteAntwoord->url.'?autoplay=0" width="100%"  height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							break;
						case 'video_vimeo':
							echo '<iframe src="'.$eersteAntwoord->url.'" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							break;
						case 'remote_document':
							break;
					}
					echo '<hr /></div></div>';					
					$d = $eersteAntwoord;
				}
			?>
			</div></div>
		</div>
	@if ($teller%6==5)
	</div>
	<div class="row" style="max-width: 100%;">
	@endif
	<?php
		$teller++;
	 }
	?>
	</div>
	
	<?php if (isset($auteurs)) { ?>
	<div class="row" style="max-width: 100%">
		<div class="small-12 columns">
			<h4>Bekijk bijdragen per auteur</h4>
			@foreach ($auteurs as $auteur)
			{!! HTML::link('search/author/'.$auteur->auteur, $auteur->auteur) !!} - 
			@endforeach
		</div>
	</div>
	<?php } ?>
	
	<div class="row" style="max-width: 100%">
		<div class="medium-10 columns">
		</div>
		<div class="medium-2 columns">
			@if ($user)
			<a href="#" data-reveal-id="myModal">Start een nieuwe discussie</a>
			@else
			<p>Om discussies te initiëren, moet je aangemeld zijn</p>
			@endif
			
		</div>
	</div>

	@if ($user)
	<div id="myModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
		<h2 id="modalTitle">Nieuwe discussie</h2>
		<p class="lead">Start een nieuwe discussie a.d.h.v. een foto, tekst, video,...</p>
		{!! Form::open(array('id'=>'commentForm','url'=>'comment','method'=>'POST', 'files'=>true, 'class' => 'dropzone')) !!}
		<fieldset>
			<legend>Algemene informatie</legend>
			<div class="large-12 columns">
				<label for="titel">Titel van mijn antwoord:</label>{!! Form::text('titel',null, array('size' => 50)); !!}
			</div>
			<div class="large-12 columns">
				<label for="tagNieuw1">Tag 1:</label>{!! Form::text('tagNieuw1',null, array('size' => 50, 'class'=>'tags')); !!}
			</div>
			<div class="large-12 columns">
				<label for="tagNieuw2">Tag 2:</label>{!! Form::text('tagNieuw2',null, array('size' => 50, 'class'=>'tags')); !!}
			</div>
			<div class="large-12 columns">
				<label for="tagNieuw3">Tag 3:</label>{!! Form::text('tagNieuw3',null, array('size' => 50, 'class'=>'tags')); !!}
			</div>
		</fieldset>
		<fieldset>
			<legend>Kies een van de volgende:</legend>
			<div class="large-12 columns">
				<label for="inhoud">Tekst:</label>{!! Form::textarea('inhoud', null, ['class' => 'oneRequired']); !!}
			</div>
			<div class="large-12 columns">
				<label for="url">URL van de bijdrage (YouTube, Vimeo,...)</label>{!! Form::text('url', null, ['class' => 'oneRequired'])!!}
				<p>Youtube: https://www.youtube.com/embed/jWMIU2DRrK0<br />Vimeo: https://player.vimeo.com/video/7338120</p>
			</div>
			<div class="large-12 columns">
				<label for="artefact_image">of upload een bestand (jpg, jpeg, pdf, png, gif)</label>{!! Form::file('artefact_image', ['class' => 'oneRequired']) !!}
			</div>
		</fieldset>
		<fieldset>
			<legend>Extra uitleg</legend>
			<label for="artefact_image">Document: </label>{!! Form::file('artefact_attachment') !!}
		</fieldset>
		{!! Form::submit() !!}
	    {!! Form::close() !!}
		<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>
	@endif
	
	<footer class="row">
		<div class="large-12 columns">
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
	
	<script>
	$(document).foundation().foundation('joyride', 'start');
	function split( val ) {
		return val.split( /,\s*/ );
	}

	function extractLast( term ) {
		return split( term ).pop();
	}
	
	$(function() {
		$('#commentForm').validate({
			rules: {
				'titel': { required: true },
				'tagNieuw1': { required: true },
				'tagNieuw2': { required: true },
				'tagNieuw3': { required: true },
				'inhoud': { require_from_group: [1, '.oneRequired'] },
				'url': { require_from_group: [1, '.oneRequired'] },
				'artefact_image': { require_from_group: [1, '.oneRequired'], extension: "jpg|jpeg|pdf|png|gif" }
			},
			messages: {
				'titel': { required: 'De titel is verplicht' },
				'tagNieuw1': { required: 'Je moet een nieuwe tag toevoegen' },
				'tagNieuw2': { required: 'Je moet een nieuwe tag toevoegen' },
				'tagNieuw3': { required: 'Je moet een nieuwe tag toevoegen' },
				'inhoud': { require_from_group: 'Geef één van de volgende in: tekst, foto/video-URL, bestand'},
				'url': { require_from_group: 'Geef één van de volgende in: tekst, foto/video-URL, bestand'},
				'artefact_image': { require_from_group: 'Geef één van de volgende in: tekst, foto/video-URL, bestand'}
			},
			errorPlacement: function(error, element) {
				if ( element.is(":checkbox")) {
					error.appendTo( element.parents('.container') );
				} else { // This is the default behavior
					error.insertAfter( element );
				}
			}
		});
		$(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
			var modal = $(this);
			$('.tags')
				.bind("keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).autocomplete( "instance" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					source: function( request, response ) {
						$.getJSON( "{{ URL::to('tags/') }}" + "/" + extractLast(request.term), null,
							function(result) { $('.ui-autocomplete').addClass('f-dropdown'); response(result)}
						);
					},
					search: function() {
						var term = extractLast( this.value );
						//if ( term.length < 2 ) return false;
					},
					focus: function() { return false; },
					select: function( event, ui ) {
						this.value = ui.item.value;
						return false;
					}
				});
		});
		$("iframe").each(function(){
			var ifr_source = $(this).attr('src');
			var wmode = "wmode=transparent";
			if(ifr_source.indexOf('?') != -1) $(this).attr('src',ifr_source+'&'+wmode);
			else $(this).attr('src',ifr_source+'?'+wmode);
		});
	});
    </script>
	</body>
</html>
