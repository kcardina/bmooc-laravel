<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="img/favicon.ico">
    <!-- webfonts -->
    {!! HTML::style('https://fonts.googleapis.com/css?family=Muli:400,300') !!}
    {!! HTML::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css') !!}
    <!-- stylesheets -->
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('//cdn.quilljs.com/0.20.1/quill.snow.css') !!}
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
			    <div class="small-12 columns text-right">
                    <nav class="main">
                        <ul class="inline slash">
                           <li>
                                {!! HTML::link('#', 'help', array('help-show' => 'index')) !!}
                            </li>
                            <li>
                                {!! HTML::link('#', 'about', array('data-reveal-id' => 'help')) !!}
                            </li>
                            <li>
                                {!! HTML::link('#', 'feedback', array('data-reveal-id' => 'feedback')) !!}
                            </li>
                            <li>
                                @if (isset($user))
						{!! HTML::link('auth/logout','Sign out', array('class'=>'logout')) !!}
					@else
						{!! HTML::link('auth/login','Sign in', ['class'=>'logout']) !!} 
					@endif
                            </li>
                            <li>
                                @if (!isset($user))
                                        {!! HTML::link('auth/register','Register', ['class'=>'logout']) !!}
                                @endif
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row large">
				<div class="small-4 medium-3 large-2 columns">
					<h1>{!! HTML::link('/','bMOOC') !!}</h1>
				</div>
				<div class="small-8 medium-9 large-3 columns">
					@if (isset($user) && $user->role=="editor")
                        <button class="big plus pullup" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic">Start a new topic</button>
                    @endif
				</div>
				<div class="large-7 columns" data-help="index" data-help-id="search">
                   <nav class="sort">
                     <form class="sort">
                      <div class="row sort">
                           <div class="medium-4 columns form-inline">
                            <label for="auteurs">Authors</label>
                               <span class="field">
                                 <select name="auteurs" id="auteurs">
                                  <option value ="all">All</option>
                                    <option disabled>──────────</option>
                                    @foreach ($auteurs as $auteur)
                                      @if(isset($search))
                                           @if($auteur->id == $search['author'])
                                            <option value="{{ $auteur->id }}" selected>{{ $auteur->name }}</option>
                                            @else
                                            <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                                            @endif
                                        @else
                                        <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                                        @endif
                                    @endforeach
                                   </select>
                               </span>
                           </div>
                           <div class="medium-4 columns form-inline">
                               <label for="tags">Tags</label>
                               <span class="field">
                               <select name="tags" id="tags">
                                  <option value="all">All</option>
                                    <option disabled>──────────</option>
                                    @foreach ($tags as $tag)
                                      @if(isset($search))
                                           @if($tag->id == $search['tag'])
                                            <option value="{{ $tag->id }}" selected>{{ $tag->tag }}</option>
                                            @else
                                            <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                                            @endif
                                        @else
                                        <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                                        @endif
                                    @endforeach
                                                           </select>
                               </span>
                           </div>
                           <div class="medium-4 columns form-inline">
                               <label for="zoek">Search</label>
                               <span class="field">
                                @if(isset($search))
                                    <input type="text" id="zoek" value="{{ $search['keyword'] }}"/>
                                @else
                                    <input type="text" id="zoek" />
                                @endif
                               </span>
                           </div>
                       </div>
                       </form>
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
    	
    		<div class="row item" data-id="{{ $topic->id }}">
            <div class="large-5 columns">
                <h2>{{ $topic->title }}</h2>
                <div class="extra laatste_wijziging">
                   initiated
                   <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->created_at)) }} {{ date('H:i', strtotime($topic->created_at)) }}</span>
                   by
                    <span class="lightgrey"><a href="{{ URL::to('/') }}/search/{{ $topic->the_author->id}}">{{ $topic->the_author->name}}</a></span><br />
                </div>
                @if (isset($topic->last_modified))
                    <div class="extra laatste_wijziging">
                        last addition
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->last_modified)) }} {{ date('H:i', strtotime($topic->last_modified)) }}</span>
                        by
                        <span class="lightgrey"><a href="{{ URL::to('/') }}/search/{{ isset($topic->last_modifier) ? $topic->last_modifier->id : $topic->the_author->id}}">{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</a></span>
                    </div>
                @endif
            </div>
            <div class="info">
                <div class="large-3 columns laatste_wijziging">
                   last addition
                   @if (isset($topic->last_modified))
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->last_modified)) }}</span>
                        by
                    <span class="lightgrey"><a href="{{ URL::to('/') }}/search/{{ isset($topic->last_modifier) ? $topic->last_modifier->id : $topic->the_author->id}}">{{ isset($topic->last_modifier) ? $topic->last_modifier->name : $topic->the_author->name}}</a></span>
                    @else
                        <span class="lightgrey">{{ date('d/m/Y', strtotime($topic->created_at)) }}</span>
                        by
                    <span class="lightgrey"><a href="{{ URL::to('/') }}/search/{{ $topic->the_author->id}}">{{ $topic->the_author->name}}</a></span>
                    @endif
                </div>
                <div class="large-1 columns antwoorden">
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
                <div class="large-2 columns">
                    tags:
                    <ul class="inline slash">
                    @foreach ($topic->tags as $tag)
                        <li><a href="{{ URL::to('/') }}/search/all/{{$tag->id}}">{{$tag->tag}}</a></li>
                    @endforeach
                    </ul>
                </div>
                <div class="large-1 columns instruction text-right">
                		<button class="small information" data-reveal-id="artefact_lightbox_left" data-help="topic" data-help-id="details"></button>
                </div>
            </div>
            <div class="extra">
                <div class="large-10 columns antwoorden">
                <ul class="inline arrow"></ul>
            </div>
            </div>
        </div>
        <!-- END item -->
	@endforeach
        
    </div>
    
    <!-- ADD NEW TOPIC #new_topic -->
    @if (isset($user) && $user->role == 'editor')
    <div id="new_topic" class="reveal-modal slide screen-right" data-reveal data-options="animation_speed: 0" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <div class="row">  
           <div class="large-2 show-for-large-up columns">
               <img src="{{ asset('img/plus_plain.png') }}" alt="plus"/>
            </div>
            <div class="large-8 medium-12 columns end">
            	{!! Form::open(array('id'=>'newTopicForm', 'data-abide', 'onsubmit'=>'return validate("newTopicForm")', 'url'=>'topic/new','method'=>'POST', 'files'=>true)) !!}
                <h2>Start a new topic</h2>
                <p>Initiate a topic using text, an image, a video or a document. This will be the first contribution to the topic.</p>
                <fieldset>
                    <h3>General information</h3>
                    <!-- INPUT: topic_title -->
                    <div class="field_title">
                        <label>Title:
                            <input type="text" required name="topic_title"/>
                        </label>
                        <small class="error">Please enter a title for the topic.</small>
                    </div>
                    <!-- CHECKBOX: topic_new_tag -->
                    <label>Tags (enter 3 below):</label>
                    <div class="form-inline">
                        <div class="field_tag">
                            <label for="new-tag-1">Tag 1</label>
                            <span class="field">
                                <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-1" type="text" name="topic_new_tag[]"/>
                                <small class="error">3 different tags are required.</small>
                            </span>
                        </div>
                        <div class="field_tag">
                            <label for="new-tag-2">Tag 2</label>
                            <span class="field">
                               <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-2" type="text" name="topic_new_tag[]"/>
                                <small class="error">3 different tags are required.</small>
                            </span>
                        </div>
                        <div class="field_tag">
                            <label for="new-tag-3">Tag 3</label>
                            <span class="field">
                                <input required data-abide-validator="tag_new" class="new-tag" id="new-tag-3" type="text" name="topic_new_tag[]"/>
                                <small class="error">3 different tags are required.</small>
                            </span>
                        </div>
                    </div>
                </fieldset>
                <fieldset> <!-- BUTTONS: topic_button_xxx -->
                    <h3>Add text, an image, a video or a document:</h3>
                    <div class="filetype">
                       <!-- buttons -->
                        <div class="row large" data-equalizer>
                           <div class="small-6 large-3 columns text-center" data-equalizer-watch id="topic_button_text">
                               <button class="square purple type_select" id="type_text">
                                   <i class="fa fa-align-justify"></i>
                                   text
                               </button>
                           </div>
                           <div class="small-6 large-3 columns text-center" data-equalizer-watch id="topic_button_image">
                               <button class="square purple type_select" id="type_image">
                                   <i class="fa fa-camera"></i>
                                   image<br /><small>(jpg, png, gif)</small>
                               </button>
                           </div>
                            <div class="small-6 large-3 columns text-center" data-equalizer-watch id="topic_button_video">
                               <button class="square purple type_select" id="type_video">
                                <i class="fa fa-video-camera"></i>
                                  video<br /><small>(youtube, vimeo)</small>
                              </button>
                           </div>
                            <div class="small-6 large-3 columns text-center end" data-equalizer-watch id="topic_button_file">
                               <button class="square purple type_select" id="type_file">
                               <i class="fa fa-file"></i>
                               document<br /><small>(pdf)</small>
                               </button>
                           </div>
                        </div>
                        <!-- input fields -->
                        <div class="row type_input input_textarea" id="topic_input_text" style="display: none;">
                           <div class="small-12 columns ql_wrapper">
                                <!-- Create the toolbar container -->

                                <div class="ql_toolbar" class="toolbar ql-toolbar ql-snow">
                                    <span class="ql-format-group">
                                        <select title="Size" class="ql-size">
                                            <option value="0.8rem">Small</option>
                                            <option value="1rem" selected="selected">Normal</option>
                                            <option value="1.3rem">Large</option>
                                        </select>
                                    </span>
                                    <span class="ql-format-group">
                                        <span title="Bold" class="ql-format-button ql-bold"></span>
                                        <span class="ql-format-separator"></span>
                                        <span title="Italic" class="ql-format-button ql-italic"></span>
                                        <span class="ql-format-separator"></span>
                                        <span title="Underline" class="ql-format-button ql-underline"></span>
                                        <span class="ql-format-separator"></span>
                                        <span title="Strikethrough" class="ql-format-button ql-strike"></span>
                                    </span>
                                    <span class="ql-format-group">
                                        <span title="List" class="ql-format-button ql-list"></span>
                                        <span class="ql-format-separator"></span>
                                        <span title="Bullet" class="ql-format-button ql-bullet"></span>
                                        <span class="ql-format-separator"></span>
                                        <select title="Text Alignment" class="ql-align">
                                            <option value="left" label="Left" selected=""></option>
                                            <option value="center" label="Center"></option>
                                            <option value="right" label="Right"></option>
                                            <option value="justify" label="Justify"></option>
                                        </select>
                                    </span>
                                    <span class="ql-format-group">
                                        <span title="Link" class="ql-format-button ql-link"></span>
                                    </span>
                                </div>
                            <div class="ql_editor"></div>
                            <textarea name="topic_text" style="display:none"></textarea>
                            </div>
                        </div>
                        <div class="row type_input input_file" id="topic_input_upload" style="display: none;"> <!-- Div om file upload mogelijk te maken -->
                            <div class="small-12 columns">
                                <label for="topic_upload">
                                    <span class="filetype_label">Select a file to upload <small>(&lt;2MB)</small></span>:
                                    <input data-abide-validator="filesize" type="file" id="topic_upload" name="topic_upload"/>
                                </label>
                                <small class="error">The document is too large (> 2MB).</small>
                            </div>
                        </div>
                        <div class="row type_input input_url" id="topic_input_url" style="display: none;"> <!-- Div voor url mogelijk te maken -->
                            <div class="small-12 columns">
                                <label for="topic_url">Upload or find a video on YouTube or Vimeo and paste the link to the video here:
                                    <input id="topic_url" type="text" name="topic_url"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-12 columns">
                                <small class="error filetype_error">Please choose one of the file types.</small>
                            </div>
                        </div>
                        <label>Copyright, author or reference (optional):
                            <input type="text" id="copyright" name="topic_copyright"/>
                        </label>
                        <input type="hidden" class="temp_type" name="topic_temp_type" id="topic_temp_type" />
                    </div>
                </fieldset>
                <fieldset><!-- EXTRA INFO topic_copyright, topic_attachment -->
                    <h3>Extra information (optional)</h3>
                    <label>You can attach an extra JPG, PNG, GIF or PDF file to your contribution:
                        <input type="file" data-abide-validator="filesize" id="attachment" name="topic_attachment"/>
                    </label>
                    <small class="error">The attachment is too large (> 2MB).</small>
                </fieldset>
                <input type="submit" class="full purple" value="Add topic"/>
                </form>
           </div>
        </div>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    @endif
    
    <div id="help" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <h2 id="modalTitle">bMOOC</h2>
            <p>bMOOC consists out of topics. A topic is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p>
            <p>What joins the topic, is not fixed. The topic can change its course at all times. The word "topic" derives from the Greek ta topica, which means "commonplace". The topic offers a common place of attention for (some)thing(s), a place for forms of (re)searching that may lead eventually to an artistic practice.</p>
            <p>A topic is presented by juxtapositions of images/artefacts/things. In other words, it's the relations, commonalities or positions of these things that matter. What these are is often unclear, ambiguous or polysemic.</p>
        <h3>Navigation</h3>
            <p>Navigate a topic by moving the images/artefacts/things. Intervene, explore, trouble, clarify or contribute to a topic by adding (some)thing. What you can add, depends on the topic. This could be an audio recording, a piece of text or a mystery. Push "add (some)thing" wherever you want to add/intervene/contribute, and then follow the instructions of the topic.</p>
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    <div id="feedback" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <h2 id="modalTitle">Feedback</h2>
            <p>Remarks, problems or suggestions? Please fill in the form below.</p>
               {!! Form::open(array('data-abide', 'url'=>'feedback','method'=>'POST', 'files'=>true)) !!}
               <small class="mailstatus error full"></small>
                <label for="fb_name">Name:
                    <input type="text" id="fb_name" name="fb_name"/>
                </label>
                <label for="fb_mail">E-mail:
                    <input type="email" id="fb_mail" name="fb_mail"/>
                    <small class="error">Please enter a valid e-mail address.</small>
                </label>
                <label for="fb_msg">Message:
                    <textarea required rows="5" id="fb_msg"></textarea>
                    <small class="error">Please describe your remark, problem or suggestion.</small>
                </label>
                <input type="submit" class="purple full" value="Submit"/>
            </form>
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/help.js') !!}
    {!! HTML::script('js/app.js') !!}
    {!! HTML::script('//cdn.quilljs.com/0.20.1/quill.js') !!}
    <script>
        var host = "{{ URL::to('/') }}";
        $(document).foundation();
        $(document).ready(function(){

            <?php
                // show the 'about' popup on first login
                if(!isset($_COOKIE['firstlogin'])){
                    // show popup and set cookie
                    echo "setTimeout(function(){
                        $('#help').foundation('reveal', 'open');
                    }, 2000);";
                    setcookie("firstlogin", "firstlogin", time() + 3600 * 24 * 356);
                }
            ?>

            $(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
                var modal = $(this);
                $(document).foundation('equalizer', 'reflow');
                if(modal.hasClass('slide')){
                    modal.animate({right:'0%'},500);
                }
            });

            $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
                var modal = $(this);
                if(modal.hasClass('slide')){
                    modal.animate({right:'-50%'},500);
                }
            });

            /* TOPIC TOEVOEGEN */
            $('.type_select').click(showAnswerType);
            
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
                        console.log(answer);
                        // voor UX: voeg een loading-spinner toe
                        var url = answer.url;
                        var alt = "afbeelding " + i;
                        // plaats de afbeelding
                        $(".antwoorden ul", $this).append('<a href="' + "{{ URL::to('/topic/') }}/" + answer.id + '"><li>'+ displayAnswer(answer.type.description, answer) +'</li></a>');
                    }
                });
            }

            $("nav select#auteurs").change(function(){
                $(".sort").submit();
            });
            $("nav select#tags").change(function(){
                $(".sort").submit();
            });

            $(".sort").submit(function(e){
                e.preventDefault();
                search();
            });

            function search(){
                var author = $("nav select#auteurs").val();
                var tag = $("nav select#tags").val();
                var keyword = $("nav input#zoek").val();
                window.location = host + '/search/' + author + '/' + tag + (keyword?'/' + keyword:'');
            }
        });

        function displayAnswer(type, data) {
            switch (type) {
            case 'text':
                return '<p style="width: 100px; font-size: 8px; overflow:hidden; display: inline-block">' + data.contents + '</p>';
                break;
            case 'local_image':
                return '<img src="'+ host + "/uploads/"+data.url+'"/>';
                break;
            case 'remote_image':
                return '<img src="'+data.url+'"/>';
                break;
            case 'video_youtube':
                //return '<iframe  src="'+data.url+'?autoplay=0" style="width: 100px; height: 100px; vertical-align: middle" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                var thumbnail = data.url.replace('www.youtube.com/embed', 'img.youtube.com/vi');
                return '<img src="'+ thumbnail +'/0.jpg"/>';
                break;
            case 'video_vimeo':
                return '<iframe src="'+data.url+'" style="width: 100px; height: 100px; vertical-align: middle" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                break;
            case 'local_pdf':
                return '<object data="' + host + "/uploads/" + data.url + '" type="application/pdf"><a href="' + host + "/uploads/" + data.url + '">[PDF]</a></object>';

                //return 'Please, <a href="'+ host + "/uploads/"+ data.url +'" target="_new">download</a> the document to open...';
                break;
            case 'remote_pdf':
                return 'Please, <a href="'+ data.url +'" target="_new">download</a> the document to open...';
                break;
            }
        }

        // editor
        var quill = new Quill('.ql_editor', {
            modules: {
                'toolbar': { container: '.ql_toolbar' },
                'link-tooltip': true
            },
            theme: 'snow'
        });
    </script>
  </body>
</html>
