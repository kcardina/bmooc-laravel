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
					{!! HTML::link('/help', '?') !!} -
					@if (isset($user))
						{!! HTML::link('logout','Sign out', array('class'=>'logout')) !!}
					@else
						{!! HTML::link('login/twitter','Sign in with Twitter', ['class'=>'logout']) !!}
					@endif
				</div>
				<div class="medium-7 medium-pull-2 large-pull-1 large-3 columns">
					@if (isset($user) && $user->role=="editor") <button class="newtopic">Start a new topic</button> @endif
				</div>
				<div class="large-6 large-pull-1 columns">
					<nav>
						<div class="row sort">
                   <div class="medium-4 columns">
                        <label class="form-left-label" for="auteurs">Authors</label>
                       <span class="form-left-input">
                         <select name="auteurs" id="auteurs">
                          <option>All</option>
                            <option disabled>──────────</option>
                            @foreach ($auteurs as $auteur)
                            	<option>{{ $auteur->name }}</option>
                            @endforeach
                           </select>
                       </span>
                   </div>
                   <div class="medium-4 columns">
                       <label class="form-left-label" for="tags">Tags</label>
                       <span class="form-left-input">
                       <select name="tags" id="tags">
                          <option>All</option>
                            <option disabled>──────────</option>
                            @foreach ($tags as $tag)
                            	<option>{{ $tag->tag }}</option>
                            @endforeach
                                                   </select>
                       </span>
                   </div>
                   <div class="medium-4 columns">
                       <label class="form-left-label" for="zoek">Search</label>
                       <span class="form-left-input">
                       <input type="text" id="zoek" />
                       </span>
                   </div>
                </div>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="items">
    @foreach ($topic as $topic)
        <!-- START item -->
				<?php
					$t = array();
					foreach ($topic->tags as $tag) $t[] = '"'.$tag->tag.'"';
				?>
    	
    		<div class="row item" data-id="{{ $topic->id }}" data-authors='["{{ $topic->the_author->name }}"]' data-tags='[{{ implode(',', $t) }}]'>
            <div class="large-2 columns">
                <h2>{{ $topic->title }}</h2>
                <div class="extra details laatste_wijziging">
                    modified
                    <ul>
										@if (isset($topic->last_modified))
                    		<li>{{ date('d/m/Y', strtotime($topic->last_modified)) }}</li>
                        <li>{{ date('H:i', strtotime($topic->last_modified)) }}</li>
                        <li>{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</li>
                    @else
                    		<li>{{ date('d/m/Y', strtotime($topic->updated_at)) }}</li>
                        <li>{{ date('H:i', strtotime($topic->updated_at)) }}</li>
                        <li>{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</li>
                    	@endif
                    </ul>
                </div>
            </div>
            <div class="info">
                <div class="large-3 columns details laatste_wijziging">
                    initiated	{{ date('d/m/Y', strtotime($topic->created_at)) }} by {{ $topic->the_author->name}}<br />
                    @if (isset($topic->last_modified)) 
                   		{{ date('d/m/Y', strtotime($topic->last_modified)) }} - {{ date('H:i', strtotime($topic->last_modified)) }} - 
                    	{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}} added
                    @endif
                </div>
                <div class="large-2 columns details antwoorden">
                	<?php
                		foreach ($aantalAntwoorden as $aantal) {
                			if ($aantal->thread == $topic->thread) echo '<strong>'.$aantal->aantal_antwoorden.'</strong> additions';
                		}
                   ?>
                </div>
                <div class="large-5 columns details tags">
                    tags:
                    <ul>
                    @foreach ($topic->tags as $tag)
                        <li>{{$tag->tag}}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="extra">
                <div class="large-10 columns antwoorden">
                <ul></ul>
            </div>
            </div>
        </div></a>
        <!-- END item -->
	@endforeach
        
    </div>
    
		@if (isset($user) && $user->role == 'editor')
	  <!-- ADD REPLY / NEW ITEM -->
    <div class="new" id="new_topic">
        <div class="row text-right">
            <div class="columns">
                <a href="#" class="closenew">x</a></div>
        </div>
            
        <div class="row">  
           <div class="large-2 show-for-large-up columns"><img src="{{ asset('img/plus_plain.png') }}" alt="plus"/></div>
            <div class="large-7 medium-12 columns end">
            	{!! Form::open(array('id'=>'newTopicForm','url'=>'topic/new','method'=>'POST', 'files'=>true)) !!}
                <h2>start a new topic</h2>
                <p>Initiate a topic using a video, text, photo,...</p>
                <h3>General information</h3>
                <!-- een gewone input -->
                <label>Title:
                    <input type="text" name="topic_title"/>
                </label>
                <!-- een checkbox input -->
                <label for="new-tag-1">Tags (enter 3 below):</label>
                <label for="new-tag-1">Tag 1:</label><span class="form-left-input"><input id="new-tag-1" type="text" name="topic_new_tag[]"/></span>
                <label for="new-tag-2">Tag 2:</label><span class="form-left-input"><input id="new-tag-2" type="text" name="topic_new_tag[]"/></span>
                <label for="new-tag-3">Tag 3:</label><span class="form-left-input"><input id="new-tag-3" type="text" name="topic_new_tag[]"/></span>
                
                <h3>Choose one of the following:</h3>
                <div class="row" data-equalizer>
                   <div class="small-4 columns text-center panel" data-equalizer-watch id="topic_button_text">
                       <button class="file-button purple" id="button_topic_button_text">
                           <img src="{{ asset('img/file_text.png') }}" alt="text" />
                           text
                       </button>
                   </div>
                    <div class="small-4 columns text-center panel" data-equalizer-watch id="topic_button_video">
                       <button class="file-button purple" id="button_topic_button_video">
                        <img src="{{ asset('img/file_movie.png') }}" alt="video" />
                          url<br /><small>(image, video, pdf)</small>
                      </button>
                   </div>
                    <div class="small-4 columns text-center panel end" data-equalizer-watch id="topic_button_file">
                       <button class="file-button purple" id="button_topic_button_file">
                       <img src="{{ asset('img/file_file.png') }}" alt="file" />
                       file<br /><small>(jpg, png, gif, pdf)</small>
                       </button>
                   </div>
                </div>
                <div class="row" data-equalizer id="topic_input_text" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<textarea rows="5" cols="50" name="topic_text">Type your topic text here...</textarea>
                	</div>
                </div>
                <div class="row" data-equalizer id="topic_input_upload" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="topic_upload">upload a document:</label>
                		<span class="form-left-input"><input type="file" id="topic_upload" name="topic_upload"/></span>
                	</div>
                </div>
                <div class="row" data-equalizer id="topic_input_video" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="topic_url">url:</label>
                		<span class="form-left-input"><input id="topic_url" type="text" name="topic_url"/></span>
                	</div>
                </div>
                <h3>Extra information</h3>
                <label class="form-left-label" for="attachment">document:</label>
                <span class="form-left-input"><input type="file" id="attachment" name="topic_attachment"/></span>
                
                <h3>Create topic</h3>
                <input type="hidden" name="topic_temp_type" id="topic_temp_type" />
                <input type="submit" value="Submit">
                </form>
           </div>
        </div>
    </div>
    @endif
    
    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/foundation/foundation.equalizer.js') !!}
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){
			/* TOPIC TOEVOEGEN */
			var newTopic = {
				open: function(){
					$("#new_topic").show();
					$("#new_topic").animate({right:'0'},500);
				},
				close: function(){
					$("#new_topic").animate({right:'-50%'},500, function(){
						$("#new_topic").hide();
					});
				}
			}
            
			$(".newtopic").click(function(){
				newTopic.open();
			});
            
			$(".closenew").click(function(){
				newTopic.close();
			});
			$('button.purple').click(showAnswerType);
            
			/* ANTWOORDEN LADEN */
			//boolean - bepaalt of er één (true) of meerdere (false) items tegelijk gemaximaliseerd mogen zijn
			var SINGLE = false;
            
			$(".item").click(function(e){
				//als de view niet uitgeklapt is
				if(!$(this).hasClass("active")){
					e.preventDefault();
				}
				if(SINGLE){
					$(".item").removeClass("active");
					$(".item .extra").slideUp();
					$(".item .info").show();
				}
				$(this).toggleClass("active");
				$(".info", this).toggle();
				$(".extra", this).slideToggle();
			});
            
				//bind an event listener to every item.
				// on first click: make an ajax call to load all the images & unbind the event listener
				$(".item").bind('click', loadAnswers);
					function loadAnswers(e){
						$(this).unbind(e);
						var data = $(this).data();
						var $this = $(this);
						$(".antwoorden ul", this).append('<li id="li_loader"><img class="loader" src="{{ asset("img/loader.gif") }}" alt="antwoorden worden geladen..."/></li>');
						$.getJSON(host + '/json/topic/' + data['id'] + '/answers', function(data) {
							$('#li_loader').remove();
							for(var i = 0; i < data.length; i++){
								var answer = data[i];
								// voor UX: voeg een loading-spinner toe
								var url = answer.url;
								var alt = "afbeelding " + i;
								// plaats de afbeelding
								$(".antwoorden ul", $this).append('<li><a href="' + "{{ URL::to('/topic/') }}/" + answer.id + '">'+ displayAnswer(answer.type.description, answer) +'</a></li>');
							}
						});
					}
					/* FILTERS */
					$("nav select#auteurs").change(function(){
						//toon alles
						$(".item").removeClass("hide_author");
						var s = $("option:selected", this).text();
						// if selectie != all
						if(s != $("option:first", this).text()){
							// voor ieder item
							$.each($(".item"), function(){
								var item = $(this);
								var show = 0;
								// kijk of de auteur voorkomt in de lijst
								$.each(item.data('authors'), function(index, value){
									if(value == s){
										show++;
									}
								});
								if(show == 0){
									item.addClass("hide_author");
								}
							});                    
						}
					});
            
					$("nav select#tags").change(function(){
						// toon alles
						$(".item").removeClass("hide_tag");
						var s = $("option:selected", this).text();
						// if selectie != all
						if(s != $("option:first", this).text()){
							// voor ieder item
							$.each($(".item"), function(){
								var item = $(this);
								var show = 0;
								// kijk of de auteur voorkomt in de lijst
								$.each(item.data('tags'), function(index, value){
									if(value == s){
										show++;
									}
								});
								if(show == 0){
									item.addClass("hide_tag");
								}
							});                    
						}
					});
				});

        function displayAnswer(type, data) {
        	switch (type) {
        	case 'text':
        		return '<p style="width: 100px; font-size: 8px; overflow:hidden; display: inline-block">' + data.contents + '</p>';
        		break;
        	case 'local_image':
        		return '<img src="'+ host + "/uploads/"+data.url+'">';
        		break;
        	case 'remote_image':
        		return '<img src="'+data.url+'">';
        		break;
        	case 'video_youtube':
        		//return '<iframe  src="'+data.url+'?autoplay=0" style="width: 100px; height: 100px; vertical-align: middle" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        		var thumbnail = data.url.replace('www.youtube.com/embed', 'img.youtube.com/vi');
        		return '<img src="'+ thumbnail +'/0.jpg">';
        		break;
        	case 'video_vimeo':
        		return '<iframe src="'+data.url+'" style="width: 100px; height: 100px; vertical-align: middle" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        		break;
        	case 'remote_document':
        		return 'Please, <a href="'+ data.url +'">download</a> the document to open...';
        		break;
        	case 'local_pdf':
        		break;
        	case 'remote_pdf':
        		break;
        	}
        }
			function showAnswerType(e) {
				e.preventDefault();
				var $this = $(this);
				if ($this.attr('id') == 'button_topic_button_text') {
					$('#topic_input_text').slideToggle();
					$('#topic_input_video').hide();
					$('#topic_input_upload').hide();
					$('#topic_temp_type').val('text');
				} else if ($this.attr('id') == 'button_topic_button_video') {
					$('#topic_input_text').hide();
					$('#topic_input_video').slideToggle();
					$('#topic_input_upload').hide();
					$('#topic_temp_type').val('url');
				} else if ($this.attr('id') == 'button_topic_button_file') {
					$('#topic_input_text').hide();
					$('#topic_input_video').hide();
					$('#topic_input_upload').slideToggle();
					$('#topic_temp_type').val('file');
				}
			}        
    </script>
  </body>
</html>
