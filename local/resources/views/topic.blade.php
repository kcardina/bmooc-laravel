<!doctype html>
<html class="no-js" lang="en">
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bMOOC LUCA School of Arts</title>
		<link rel="icon" type="img/ico" href="{{ URL::to('/') }}/img/favicon.ico">
    {!! HTML::style('https://fonts.googleapis.com/css?family=Questrial') !!}
    {!! HTML::style('https://fonts.googleapis.com/css?family=Open+Sans:400,700') !!}
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/lightbox.css') !!}
    {!! HTML::style('css/app.css') !!}

    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
		<header>
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
					@if (isset($user) && $user->role=="editor") <button class="new_instruction" id="open_new_instruction">New instruction</button> @endif
				</div>
				<div class="large-6 large-pull-1 columns">
					&nbsp;
				</div>
			</div>
		</header>
    <div class="topic">
        <div class="row">
        	<!-- Artefact left -->
            <div class="large-2 columns">
                <h2 id="artefact_left_title">Title</h2>
                <p id="artefact_left_description" class="description">Description here...</p>
                <div class="details laatste_wijziging">
                    modified
                    <ul>
                        <li id="artefact_left_date">12/09/2015</li>
                        <li id="artefact_left_time">21u14</li>
                        <li><a href="#" id="artefact_left_last_author">Last modified by</a></li>
                    </ul>
                </div>
                <div class="details">
                    initiator
                    <ul>
                        <li id="artefact_left_author"><a href="#">Author</a></li>
                    </ul>
                    tags
                    <ul id="artefact_left_tags">
                        <li><a href="#">List of tags here...</a></li>
                    </ul>
                    related
                    <ul id="artefact_left_related">
                        <li><a href="#">Related topics here....</a></li>
                    </ul>
                </div>
            </div>
            <div class="small-10 small-push-1 medium-5 medium-push-0 large-4 columns">
               <div class="topic_img" id="artefact_left_contents">
                </div>
                <span id="arrow_left"><a href="#" class="nav left">&larr;</a></span>
            </div>
            <!-- End Artefact left -->
            <div class="small-10 small-push-1 medium-5 medium-push-1 large-4 columns end">
               <div class="topic_img">
                    <span id="artefact_right_contents">&nbsp;</span>
                    <span id="arrow_up" style="display:none;"><a href="#" class="nav up">&uarr;</a></span>
                    <span id="arrow_right" style="display:none;"><a href="#" class="nav right">&rarr;</a></span>
                    <span id="arrow_down" style="display:none;"><a href="#" class="nav down">&darr;</a></span>
                </div>
            </div>
        </div>
      </div>
    
    
		@if (isset($user))
	  <!-- ADD REPLY / NEW ITEM -->
    <div class="new" id="new_answer">
        <div class="row text-right">
            <div class="columns">
                <a href="#" class="closenew" id="close_new_answer">x</a></div>
        </div>
            
        <div class="row">  
           <div class="large-2 show-for-large-up columns"><img src="{{ asset('img/plus_plain.png') }}" alt="plus"/></div>
            <div class="large-7 medium-12 columns end">
            	{!! Form::open(array('id'=>'commentForm','url'=>'comment','method'=>'POST', 'files'=>true, 'data-abide'=>'')) !!}
                <h2>(some)thing</h2>
                <p>add (some)thing to this topic using the form below...</p>
                <h3 id="instruction_title" style="cursor: pointer;">Instruction</h3>
                <div class="row" data-equalizer>
                	<div class="small-12 columns text-center panel" data-equalizer-watch id="instruction_content">
                	</div>
                </div>
                <h3>General information</h3>
                <!-- een gewone input -->
                <label>Title:
                    <input type="text" name="answer_title"/>
                </label>
                <!-- een checkbox input -->
                <label>Tags (select 3 below):</label>
                   <div class="tag-select" id="answer_tags">
                    </div>
                <!-- een input met een inline label -->
                <label class="form-left-label" for="new-tag">+ one new tag:</label>
                <span class="form-left-input"><input id="new-tag" type="text" name="answer_new_tag" required/></span>
                
                <h3>Choose one of the following:</h3>
                <div class="row" data-equalizer>
                   <div class="small-4 columns text-center panel" data-equalizer-watch id="answer_button_text">
                       <button class="file-button purple" id="button_answer_button_text">
                           <img src="{{ asset('img/file_text.png') }}" alt="text" />
                           text
                       </button>
                   </div>
                    <div class="small-4 columns text-center panel" data-equalizer-watch id="answer_button_video">
                       <button class="file-button purple" id="button_answer_button_video">
                        <img src="{{ asset('img/file_movie.png') }}" alt="url" />
                          url<br /><small>(image, video, pdf)</small>
                      </button>
                   </div>
                    <div class="small-4 columns text-center panel end" data-equalizer-watch id="answer_button_file">
                       <button class="file-button purple" id="button_answer_button_file">
                       <img src="{{ asset('img/file_file.png') }}" alt="file" />
                       file<br /><small>(jpg, png, gif, pdf)</small>
                       </button>
                   </div>
                </div>
                <div class="row" data-equalizer id="answer_input_text" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<textarea rows="5" cols="50" name="answer_text">Type your answer here...</textarea>
                	</div>
                </div>
                <div class="row" data-equalizer id="answer_input_upload" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="answer_upload">upload a document:</label>
                		<span class="form-left-input"><input type="file" id="answer_upload" name="answer_upload"/></span>
                	</div>
                </div>
                <div class="row" data-equalizer id="answer_input_video" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="answer_url">url:</label>
                		<span class="form-left-input"><input id="answer_url" type="text" name="answer_url"/></span>
                	</div>
                </div>
                <h3>Extra information</h3>
                <label class="form-left-label" for="attachment">document:</label>
                <span class="form-left-input"><input type="file" id="attachment" name="answer_attachment"/></span>
                
                <h3>Add it!</h3>
                <input type="hidden" name="answer_temp_type" id="answer_temp_type" />
                <input type="hidden" name="answer_parent" id="answer_parent" />
                <input type="submit" value="Submit" id="submit">
                </form>
           </div>
        </div>
    </div>
    <footer style="visibility:hidden;">
        <div class="row">
            <div class="small-2 columns"></div>
            <div class="small-10 columns">
                    <button class="newtopic" id="open_new_answer">Add (some)thing</button>
            </div>
        </div>
    </footer>
    @endif
    
		@if (isset($user) && $user->role == 'editor')
	  <!-- New Instruction -->
    <div class="new" id="new_instruction">
        <div class="row text-right">
            <div class="columns">
                <a href="#" class="closenew" id="close_new_instruction">x</a></div>
        </div>
            
        <div class="row">  
           <div class="large-2 show-for-large-up columns"><img src="{{ asset('img/plus_plain.png') }}" alt="plus"/></div>
            <div class="large-7 medium-12 columns end">
            	{!! Form::open(array('id'=>'commentForm','url'=>'instruction/new','method'=>'POST', 'files'=>true)) !!}
                <h2>new instruction</h2>
                <p>Add a new instruction for this topic. The current instruction will be disabled</p>
                <h3 id="new_instruction_title" style="cursor: pointer;">Current instruction</h3>
                <div class="row" data-equalizer>
                	<div class="small-12 columns text-center panel" data-equalizer-watch id="new_instruction_content">
                	</div>
                </div>
                <h3>General information</h3>
                <!-- een gewone input -->
                <label>Title:
                    <input type="text" name="instruction_title"/>
                </label>
                <!-- een checkbox input -->
                <label>Available answer types (check to enable):</label>
                   <div class="tag-select" id="answer_types">
										<div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="text"><span>Text</span></label></div>
										<div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="url"><span>Video</span></label></div>
										<div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="file"><span>File</span></label></div>
									</div>
                
                <h3>Choose one of the following:</h3>
                <div class="row" data-equalizer>
                   <div class="small-4 columns text-center panel" data-equalizer-watch id="instruction_button_text">
                       <button class="file-button purple" id="button_answer_button_text">
                           <img src="{{ asset('img/file_text.png') }}" alt="text" />
                           text
                       </button>
                   </div>
                    <div class="small-4 columns text-center panel" data-equalizer-watch id="instruction_button_video">
                       <button class="file-button purple" id="button_answer_button_video">
                        <img src="{{ asset('img/file_movie.png') }}" alt="video" />
                          url<br /><small>(image, video, pdf)</small>
                      </button>
                   </div>
                    <div class="small-4 columns text-center panel end" data-equalizer-watch id="instruction_button_file">
                       <button class="file-button purple" id="button_answer_button_file">
                       <img src="{{ asset('img/file_file.png') }}" alt="file" />
                       file<br /><small>(jpg, png, gif, pdf)</small>
                       </button>
                   </div>
                </div>
                <div class="row" data-equalizer id="instruction_input_text" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<textarea rows="5" cols="50" name="instruction_text">Type your answer here...</textarea>
                	</div>
                </div>
                <div class="row" data-equalizer id="instruction_input_upload" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="answer_upload">upload a document:</label>
                		<span class="form-left-input"><input type="file" id="instruction_upload" name="instruction_upload"/></span>
                	</div>
                </div>
                <div class="row" data-equalizer id="instruction_input_video" style="display: none;">
                	<div class="small-12 columns text-center panel" data-equalizer-watch>
                		<label class="form-left-label" for="answer_url">url:</label>
                		<span class="form-left-input"><input id="instruction_url" type="text" name="instruction_url"/></span>
                	</div>
                </div>
                
                <h3>Save instruction</h3>
                <input type="hidden" name="instruction_temp_type" id="instruction_temp_type" />
                <input type="hidden" name="instruction_parent" id="instruction_parent" />
                <input type="submit" value="Submit" id="submit">
                </form>
           </div>
        </div>
    </div>
    @endif
    
    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/foundation/foundation.equalizer.js') !!}
    {!! HTML::script('js/vendor/stickyFooter.js') !!}
    {!! HTML::script('js/lightbox.min.js') !!}
    {!! HTML::script('js/topic.js') !!}
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){
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
			
			showArtefactLeft({{ $artefactLeft }}, {{ isset($answerRight)? $answerRight : 0 }});
		});    
    </script>
  </body>
</html>
