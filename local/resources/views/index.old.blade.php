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
    {!! HTML::style('css/app.css?v=' . Version::get()) !!}
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
                                    {!! HTML::link('auth/logout','Sign out (' . $user->name . ')', array('class'=>'logout'))  !!}
                                @else
                                    {!! HTML::link('auth/login','Sign in', ['class'=>'logout', 'data-reveal-id'=>'signin', 'data-reveal-ajax'=>'true']) !!}
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

    @if (isset($search) && sizeof($topic) <= 0)
       <div class="item">
          <div class="row">
           <div class="columns">
               <h2>No matching topics were found.</h2>
           </div>
        </div>
       </div>
    @endif

    @foreach ($topic as $topic)
        <!-- START item -->
				<?php
					$t = array();
					foreach ($topic->tags as $tag) $t[] = '"'.$tag->tag.'"';
				?>

        <div class="item" data-id="{{ $topic->id }}">
           <div class="row">
                <div class="small-11 large-5 columns" data-help="index" data-help-id="topic_title">
                    <h2>{{ $topic->title }}</h2>
                    <div class="extra laatste_wijziging">
                       initiated by
                        <span class="lightgrey"><a href="{{ URL::to('/') }}/search/{{ $topic->the_author->id}}">{{ $topic->the_author->name}}</a></span><br />
                    </div>
                </div>
                <div class="info">
                    <div class="small-11 large-3 columns laatste_wijziging">
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
                    <div class="small-11 large-1 columns antwoorden">
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
                    <div class="small-11 large-2 columns">
                        tags:
                        <ul class="inline slash">
                        @foreach ($topic->tags as $tag)
                            <li><a href="{{ URL::to('/') }}/search/all/{{$tag->id}}">{{$tag->tag}}</a></li>
                        @endforeach
                        </ul>
                    </div>
                    <div class="small-1 columns instruction text-right">
                        @if (isset($topic->active_instruction))
                            <button data-help="index" data-help-id="view_current_instruction" class="small information" data-instruction-id="{{ $topic->active_instruction->id }}" data-instruction-added="{{ $topic->active_instruction->active_from }}" data-instruction-author="{{ $topic->active_instruction->name }}" data-instruction-title="{{ $topic->active_instruction->title }}" data-reveal-id="instruction"></button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row extra">
                <div class="small-12 columns tree">
                   <nav>
                    <button class="square purple nospace zoom-in">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button class="square purple nospace zoom-out">
                        <i class="fa fa-minus"></i>
                    </button>
                    <small>(or scroll + drag to explore)</small>
                    </nav>
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
            	{!! Form::open(array('id'=>'newTopicForm', 'data-abide'=>'ajax', 'url'=>'topic/new','method'=>'POST', 'files'=>true)) !!}
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
                                           <span class="filetype_label">Select a file to upload <small>(&lt;5MB)</small></span>:
                                            <input data-abide-validator="filesize" type="file" id="topic_upload" name="topic_upload" class="inputfile"/>
                                            <div>
                                               <span class="file_reset">
                                                    remove
                                                </span>
                                                <span>
                                                    <span class="file_label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
                                                    Choose file</span>
                                                    <span class="file_filename"></span>
                                                </span>
                                            </div>
                                    </label>
                                <small class="error">The document is too large (> 5MB).</small>
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
                        <input type="hidden" class="temp_type" name="topic_temp_type" id="topic_temp_type" data-abide-validator="filetype"/>
                    </div>
                </fieldset>
                <fieldset><!-- EXTRA INFO topic_copyright, topic_attachment -->
                    <h3>Extra information (optional)</h3>
                    <label for="attachment">
                            <span class="filetype_label">You can attach an extra JPG, PNG, GIF or PDF file to your contribution:</span>
                                <input data-abide-validator="filesize" type="file" id="attachment" name="answer_attachment" class="inputfile"/>
                                <div>
                                   <span class="file_reset">
                                        remove
                                    </span>
                                    <span>
                                        <span class="file_label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
                                        Choose file</span>
                                        <span class="file_filename"></span>
                                    </span>
                                </div>
                        </label>
                    <small class="error">The attachment is too large (> 5MB).</small>
                </fieldset>
                <input type="submit" class="full purple" value="Add topic"/>
                </form>
           </div>
        </div>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    @endif

    <div id="instruction" class="artefact_lightbox reveal-modal half" data-reveal role="dialog">
            <div class="row">
                <div class="medium-3 columns" id="instruction_metadata">
                    <h2 id="modalTitle" class="data-title">Title</h2>
                    <dl class="details">
                        <dt>Added</dt>
                        <dd class="data-added">dd/mm/yy hh:mm</dd>
                        <dt>By</dt>
                        <dd class="data-author"><a href="#">Author</a></dd>
                        <!--<dt>Accepted answer types</dt>
                        <dd class="data-answer-types"></dd>-->
                    </dl>
                    <!--
                    @if (isset($user) && $user->role == 'editor')
                    <button class="big plus" data-reveal-id="new_artefact">Add (some)thing</button>
                    @endif
                    -->
                </div>
                <div class="medium-9 columns data-item">
                    <div class="loader">
                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                    </div>
                   <div class="artefact"></div>
                </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

    <div id="help" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <h2 id="modalTitle">bMOOC</h2>
        <h3>A Massive, Open, Online Course to think with eyes and hands</h3>

        <p>The point of departure and finality of <strong>b</strong>MOOC is that, whether you are a teacher or a student, you are intrigued by 'images'.</p>

        <p>The structure of bMOOC is simple: the course consists of topics. A topic is a collection of online artefacts that are placed next to each other. A topic opens a space for gathering. The first question is: how to relate to this topic?</p>

        <p>Topics may have specific instructions. They do not determine the contribution, but ask the contributor to disclose the gaze and to become attentive for (some)thing(s).</p>

        <p>Login/register in order to join. Feel free to contribute to any topic. Click {!! HTML::link('#', 'help', array('class'=>'emphasis', 'help-show' => 'index')) !!} for assistance and {!! HTML::link('#', 'about', array('class'=>'emphasis', 'data-reveal-id' => 'help')) !!} for more information.</p>

        <div class="deep">
            <h3>Massive</h3>
            <p>The course is the embodiment of a common commitment, it is a collective affair. A contribution never stands on its own, but is always related to other contributions within a topic. In their mutual relationship the different contributions bring something collectively to life: a massif is formed and takes shape.</p>

            <h3>Open</h3>
            <p>Nobody knows in advance the final destination of his/her contribution(s): to what it contributes and what it brings about. The direction and the content of the course is not fixed, or pre-conceived, but is formed and shaped by everyone's contribution.</p>

            <h3>Online</h3>
            <p>The word 'topic' derives from the Greek ta topica, and means commonplace. Several contributions are placed in the same space. The online space of topics collects individuals around "something". The linear narrative structure of a classic course is interrupted. No program, but a network shows itself. The contributions represent a shared research practice where possible connections and interests become visible.</p>

            <h3>Course</h3>
            <p>Contributions are not random, they imply a certain care for images. Images exist and never stand alone. They always have a context in which they are embedded and from which they make sense. Therefore the course creates structures that urge us to become attentive for something and to create new meanings (as non sense).</p>

            <p class="small"><em>bMOOC is a OOF- Research project by LUCA School of Arts (Art, Practices &amp; Education) and KU Leuven (Laboratory for Education and Society), commissioned by Association KU Leuven.</em></p>

            <p class="small"><strong>bMOOC is a constant test-run prototype: please {!! HTML::link('#', 'contact us', array('class'=>'emphasis', 'data-reveal-id' => 'feedback')) !!} with your suggestions.</strong></p>
        </div>
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    <div id="feedback" class="reveal-modal small" data-reveal aria-labelledby="feedback_title" aria-hidden="true" role="dialog">
        <h2 id="feedback_title">Feedback</h2>
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

    <div id="progress" class="reveal-modal small" data-reveal aria-hidden="true" role="dialog">
       <div class="row">
           <div class="columns small-12 text-center">
                <div class="loader">
                    <br /><br /><br />
                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                </div>
                <p class="message">Loading...</p>
           </div>
       </div>
    </div>

    <div id="signin" class="reveal-modal tiny" data-reveal role="dialog" aria-hidden="true">
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    <div id="signup" class="reveal-modal tiny" data-reveal role="dialog" aria-hidden="true">
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/help.js?v=' . Version::get()) !!}
    {!! HTML::script('js/app.js?v=' . Version::get()) !!}
    {!! HTML::script('//cdn.quilljs.com/0.20.1/quill.js') !!}
    {!! HTML::script('js/imagesloaded.min.js') !!}
    {!! HTML::script('js/jquery.form.min.js') !!}
    {!! HTML::script('js/pdf.js') !!}
    {!! HTML::script('//d3js.org/d3.v3.min.js') !!}
    {!! HTML::script('js/d3plus.min.js') !!}
    <script>
        var host = "{{ URL::to('/') }}";
        $(document).foundation();
        $(document).ready(function(){

            <?php
                // show the 'about' popup on first login
                if(!isset($_COOKIE['firstlogin'])){
                    // show popup and set cookie
                    echo "$('#help .deep').hide();";
                    echo "setTimeout(function(){
                        $('#help').foundation('reveal', 'open');
                    }, 2000);";
                    echo "$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
                      var modal = $(this);
                      modal.find('.deep').show();
                    });";
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

            $('button[data-reveal-id="instruction"]').click(function(e){
                if ($(this).attr('data-rev-id')) return false;
                // $(document).on('open.fndtn.reveal', '#instruction', function () {
                e.stopPropagation();

                $("#instruction .data-title").html($(this).data('instruction-title'));
                $("#instruction .data-added").html(parseDate($(this).data('instruction-added')));
                $("#instruction .data-author").html($(this).data('instruction-author'));
                $("#instruction .artefact").hide();
                $("#instruction .loader").show();

                $('#instruction').foundation('reveal', 'open');



                var data = $(this).parents(".item").data();
                $.getJSON(host + '/json/topic/' + data['id'], function(data){
                    render($('#instruction'), data.instruction[0].instruction_type.description, data.instruction[0]);
                });
            });

            $(".item a").click(function(e){
                e.stopImmediatePropagation();
            });

            $(".item").click(function(e){
                // als de view niet uitgeklapt is
                if(!$(this).hasClass("active")){
                    // vorige inklappen
                    $(".item").removeClass("active");
                    $(".item .extra").hide();
                    // show new one
                    $(this).toggleClass("active");
                    // maak grootte van scherm (met javascript, want svg kan niet in % of vh container
                    resizeItem($(this));

                    // scroll naar boven
                    // maak info grootte van scherm
                    $('html,body').animate({
                        scrollTop: $(this).offset().top - 40
                    },
                    'slow');
                    $(".extra", this).slideToggle();
                }
            });

            function resizeItem(item){
                var OFFSET = 40;

                var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

                $('.tree', item).height(h - document.getElementsByTagName('header')[0].offsetHeight - OFFSET * 2);
            }

            //bind an event listener to every item.
            // on first click: make an ajax call to load all the images & unbind the event listener
            $(".item").bind('click', loadAnswers);

            function loadAnswers(e){
                if(parseInt("{!!  isset($user) !!}") != 1){
                    $('a.logout').click();
                    return false;
                }
                $(this).unbind(e);
                var data = $(this).data();
                var $this = $(this);
                var author = $("nav select#auteurs").val();
                var tag = $("nav select#tags").val();
                var keyword = $("nav input#zoek").val();
                $.getJSON(host + '/json/topic/' + data['id'] + '/answers/search/' + author + '/' + tag + '/' + keyword, function(data) {
                   var tree = new Tree($('.tree', $this).get(0), data);
                    tree.draw();
                    tree.fit();
                    if(tree.hasZoom){
                        $('nav', $this).show();
                        $('.zoom-in', $this).click(function(){
                            tree.zoom(0.1);
                        });
                        $('.zoom-out', $this).click(function(){
                            tree.zoom(-0.1);
                        });
                    }
                    $('.tree', $this).on('mousedown', function(){
                        $(this).addClass('move');
                    });
                    $('.tree', $this).on('mouseup', function(){
                        $(this).removeClass('move');
                    });
                    // handle resize
                    $(window).on('resize', function(){
                        if($this.hasClass('active')){
                            resizeItem($this);
                            tree.fit();
                        }
                    });
                    // handle reopen
                    $('.row', $this).first().on('click', function(e){
                        if($this.hasClass('active')) {
                            $(".item .extra").slideUp(function(){
                                $(".item").removeClass("active");
                            });
                            e.stopPropagation();
                        } else {
                            setTimeout( function(){
                                tree.fit()
                            }, 400 ); // wait for toggle animation to finish
                        }
                    });
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

        @if (isset($user))
        // editor
        var quill = new Quill('.ql_editor', {
            modules: {
                'toolbar': { container: '.ql_toolbar' },
                'link-tooltip': true
            },
            theme: 'snow'
        });
        @endif

    </script>
  </body>
</html>
