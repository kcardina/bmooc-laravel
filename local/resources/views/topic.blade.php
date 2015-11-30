<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="{{ URL::to('/') }}/img/favicon.ico">
    <!-- webfonts -->
    {!! HTML::style('https://fonts.googleapis.com/css?family=Muli:400,300') !!}
    <!-- stylesheets -->
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/app.css') !!}
    <!-- scripts -->
    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
       <div id="container" class="full">
        <header>
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
				<div class="small-12 medium-9 large-3 columns end">
					@if (isset($user) && $user->role=="editor")
                        <button class="big plus pullup" data-reveal-id="new_instruction">New instruction</button>
                    @endif
				</div>
            </div>
		</header>

    <div class="topic">
        <div class="row fullflex">
            <div class="small-6 columns full">
               <div class="artefact loader" id="artefact_left_loader">
                    <img src="/img/loader_dark_big.gif" alt="loading...">
               </div>
               <div class="artefact" id="artefact_left_contents" data-reveal-id="artefact_lightbox_left"></div>
            </div>
            <div class="small-6 columns full">
                <div class="artefact loader" id="artefact_left_loader">
                    <img src="/img/loader_dark_big.gif" alt="loading...">
               </div>
                <div class="artefact" id="artefact_right_contents" data-reveal-id="artefact_lightbox_right"></div>
            </div>
        </div>
        <nav>
           <div class="nav" id="nav_up">&uarr;</div>
           <div class="nav" id="nav_right">&rarr;</div>
           <div class="nav" id="nav_down">&darr;</div>
           <div class="nav" id="nav_left">&larr;</div>
       </nav>
      </div>
    </div>
    
    <div id="artefact_lightbox_left" class="artefact_lightbox reveal-modal full" data-reveal role="dialog">
       <div class="row">
           <div class="large-3 columns">
               <h2 id="modalTitle" class="data-title">Title</h2>
                <dl class="details">
                  <dt>Added</dt>
                  <dd class="data-added">dd/mm/yy hh:mm</dd>
                  <dt>By</dt>
                  <dd class="data-author"><a href="#">John Doe</a></dd>
                  <dt>Tags</dt>
                  <dd>
                      <ul class="inline slash data-tags">
                          <li><a href="#">tag 1</a></li>
                          <li><a href="#">tag 2</a></li>
                          <li><a href="#">tag 3</a></li>
                      </ul>
                  </dd>
                  <!--
                  <dt>Related</dt>
                  <dd>
                      <ul class="block">
                          <li><a href="#">related 1</a></li>
                          <li><a href="#">related 2</a></li>
                          <li><a href="#">related 3</a></li>
                      </ul>
                  </dd>
                  -->
                </dl>
                @if (isset($user) && $user->role == 'editor')
                    <button class="big plus" data-reveal-id="new_artefact">Add (some)thing</button>
                @endif
           </div>
           <div class="large-9 columns data-item">
                Item
           </div>
       </div>

        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>

    <div id="artefact_lightbox_right" class="artefact_lightbox reveal-modal full" data-reveal role="dialog">
       <div class="row">
           <div class="large-3 columns">
               <h2 id="modalTitle" class="data-title">Title</h2>
                <dl class="details">
                  <dt>Added</dt>
                  <dd class="data-added">dd/mm/yy hh:mm</dd>
                  <dt>By</dt>
                  <dd class="data-author"><a href="#">Author</a></dd>
                  <dt>Tags</dt>
                  <dd>
                      <ul class="inline slash data-tags">
                          <li><a href="#">tag 1</a></li>
                          <li><a href="#">tag 2</a></li>
                          <li><a href="#">tag 3</a></li>
                      </ul>
                  </dd>
                  <!--
                  <dt>Related</dt>
                  <dd>
                      <ul class="block">
                          <li><a href="#">related 1</a></li>
                          <li><a href="#">related 2</a></li>
                          <li><a href="#">related 3</a></li>
                      </ul>
                  </dd>
                  -->
                </dl>
                @if (isset($user) && $user->role == 'editor')
                    <button class="big plus" data-reveal-id="new_artefact">Add (some)thing</button>
                @endif
           </div>
           <div class="large-9 columns data-item">
               Item
           </div>
       </div>

        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    
    <div id="help" class="reveal-modal" data-reveal aria-labelledby="Help" aria-hidden="true" role="dialog">
        <h2 id="modalTitle">bMOOC</h2>
            <p>bMOOC consists out of topics. A topic is a cluster, a collection of online things that join into some form or shape. This can be a conversation, a discussion, a tension or a kind of unspeakable resonance.</p>
            <p>What joins the topic, is not fixed. The topic can change its course at all times. The word "topic" derives from the Greek ta topica, which means "commonplace". The topic offers a common place of attention for (some)thing(s), a place for forms of (re)searching that may lead eventually to an artistic practice.</p>
            <p>A topic is presented by juxtapositions of images/artefacts/things. In other words, it's the relations, commonalities or positions of these things that matter. What these are is often unclear, ambiguous or polysemic.</p>
        <h3>Navigation</h3>
            <p>Navigate a topic by moving the images/artefacts/things. Intervene, explore, trouble, clarify or contribute to a topic by adding (some)thing. What you can add, depends on the topic. This could be an audio recording, a piece of text or a mystery. Push "add (some)thing" wherever you want to add/intervene/contribute, and then follow the instructions of the topic.</p>
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    
    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/flexie.min.js') !!}
    {!! HTML::script('js/topic.js') !!}
    {!! HTML::script('js/imagesloaded.min.js') !!}
    {!! HTML::script('js/pointer-events-polyfill.js') !!}
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){

            //polyfill
            PointerEventsPolyfill.initialize({selector: 'iframe'});

			/* ANSWER TOEVOEGEN */
			var newTopic = {
				open: function(){
					$("#new_answer").show();
					$("#new_answer").animate({right:'0'},500);
				},
				close: function(){
					$("#new_answer").animate({right:'-50%'},500, function(){
						$("#new_answer").hide();
					});
				}
			}
			$("#open_new_answer").click(function(){
				newInstruction.close();
				newTopic.open();
			});
			$("#close_new_answer").click(function(){
				newTopic.close();
			});
			$('#new_answer button.purple').click(showAnswerType);

			/* INSTRUCTION TOEVOEGEN */
			var newInstruction = {
				open: function(){
					$("#new_instruction").show();
					$("#new_instruction").animate({right:'0'},500);
				},
				close: function(){
					$("#new_instruction").animate({right:'-50%'},500, function(){
						$("#new_instruction").hide();
					});
				}
			}
			$("#open_new_instruction").click(function(){
				newTopic.close();
				newInstruction.open();
			});
			$("#close_new_instruction").click(function(){
				newInstruction.close();
			});
			$('#new_instruction button.purple').click(showInstructionType);

            $('#artefact_left_contents').hide();
            $('#artefact_right_contents').hide();

			showArtefactLeft({{ $artefactLeft }}, {{ isset($answerRight)? $answerRight : 0 }});

            document.onkeydown = function(evt) {
                evt = evt || window.event;
                switch (evt.keyCode) {
                    case 37:
                        //left
                        $('#nav_left a').click();
                        break;
                    case 38:
                        //up
                        $('#nav_up a').click();
                        break;
                    case 39:
                        //right
                        $('#nav_right a').click();
                        break;
                    case 40:
                        //down
                        $('#nav_down a').click();
                        break;
                }
            };

		});    
    </script>
  </body>
</html>
