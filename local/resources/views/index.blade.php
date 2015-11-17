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
		<header class="green">
			<div class="row large">
			    <div class="small-12 columns text-right">
                    <nav class="main">
                        <ul class="inline slash">
                            <li>
                                {!! HTML::link('#', 'help', array('data-reveal-id' => 'help')) !!}
                            </li>
                            <li>
                                @if (isset($user))
						{!! HTML::link('logout','Sign out', array('class'=>'logout')) !!}
					@else
						{!! HTML::link('login/twitter','Sign in with Twitter', ['class'=>'logout']) !!}
					@endif
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row large">
				<div class="small-3 large-2 columns">
					<h1>{!! HTML::link('/','bMOOC') !!}</h1>
				</div>
				<div class="small-12 medium-9 large-3 columns">
					@if (isset($user) && $user->role=="editor")
                        <button class="big plus newtopic">Start a new topic</button>
                    @endif
				</div>
				<div class="large-7 columns">
                   <nav class="sort">
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
                <div class="extra laatste_wijziging">
                   initiated
                   <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->created_at)) }} {{ date('H:i', strtotime($topic->created_at)) }}</span>
                   by
                   <span class="lightgrey">{{ $topic->the_author->name}}</span><br />
                </div>
                @if (isset($topic->last_modified))
                    <div class="extra laatste_wijziging">
                        last addition
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->last_modified)) }} {{ date('H:i', strtotime($topic->last_modified)) }}</span>
                        by
                    <span class="lightgrey">{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</span>
                    </div>
                @endif
            </div>
            <div class="info">
                <div class="large-3 columns laatste_wijziging">
                   last addition
                   @if (isset($topic->last_modified))
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->last_modified)) }}</span>
                        by
                    <span class="lightgrey">{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</span>
                    @else
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->created_at)) }}</span>
                        by
                    <span class="lightgrey">{{ $topic->the_author->name}}</span>
                    @endif
                </div>
                <div class="large-2 columns antwoorden">
                		@foreach ($aantalAntwoorden as $aantal)
                			@if ($aantal->thread == $topic->thread)
                			 <strong>{{ $aantal->aantal_antwoorden }}</strong>
                                 @if ($aantal->aantal_antwoorden == 1)
                                     addition
                                 @else
                                     additions
                                 @endif
                			 @endif
                		@endforeach
                </div>
                <div class="large-5 columns">
                    tags:
                    <ul class="inline slash">
                    @foreach ($topic->tags as $tag)
                        <li>{{$tag->tag}}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="extra">
                <div class="large-10 columns antwoorden">
                <ul class="inline arrow"></ul>
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
    
    <div id="help" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <h3 id="modalTitle">bMOOC</h3>
            <p>bMOOC consists out of topics. A topic is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p>
            <p>What joins the topic, is not fixed. The topic can change its course at all times. The word "topic" derives from the Greek ta topica, which means "commonplace". The topic offers a common place of attention for (some)thing(s), a place for forms of (re)searching that may lead eventually to an artistic practice.</p>
            <p>A topic is presented by juxtapositions of images/artefacts/things. In other words, it's the relations, commonalities or positions of these things that matter. What these are is often unclear, ambiguous or polysemic.</p>
        <h3>Navigation</h3>
            <p>Navigate a topic by moving the images/artefacts/things. Intervene, explore, trouble, clarify or contribute to a topic by adding (some)thing. What you can add, depends on the topic. This could be an audio recording, a piece of text or a mystery. Push "add (some)thing" wherever you want to add/intervene/contribute, and then follow the instructions of the topic.</p>
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>

    </div>

    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
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
                $(document).foundation();
			});
            
			$(".closenew").click(function(){
				newTopic.close();
			});
			$('button.purple').click(showAnswerType);
            
			/* ANTWOORDEN LADEN */
			            // boolean - bepaalt of er één (true) of meerdere (false) items tegelijk gemaximaliseerd mogen zijn
            var SINGLE = true;
            
            $(".item a").click(function(e){
                e.stopImmediatePropagation();
            });

            $(".item").click(function(e){
                // als de view niet uitgeklapt is
                if(!$(this).hasClass("active")){
                    if(SINGLE){
                        $(".item").removeClass("active");
                        $(".item .extra").slideUp();
                        $(".item .info").show();
                    }
                    $(this).toggleClass("active");
                    $(".info", this).toggle();
                    $(".extra", this).slideToggle();
                }
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
