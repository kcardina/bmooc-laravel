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
                    <div class="small-12 large-10 columns end">
                        <button class="big information pullup space" data-reveal-id="instruction">Topic instruction</button>
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
                            {!! HTML::image(asset("img/loader_dark_big.gif"), 'loading...') !!}
                        </div>
                        <div class="artefact" id="artefact_left_contents" data-reveal-id="artefact_lightbox_left"></div>
                    </div>
                    <div class="small-6 columns full">
                        <div class="artefact loader" id="artefact_right_loader">
                            {!! HTML::image(asset("img/loader_dark_big.gif"), 'loading...') !!}
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
                        <dt>Attachment</dt>
                        <dd class="data-attachment"><a href="#" target="_new">File</a></dd>
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
                    <button id="button_add_left" class="big plus" data-reveal-id="new_artefact"  data-artefact="left">Add (some)thing</button>
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
                        <dt>Attachment</dt>
                        <dd class="data-attachment"><a href="#" target="_new">File</a></dd>
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
                    @if (isset($user))
                    <button id="button_add_right" class="big plus" data-reveal-id="new_artefact"  data-artefact="right">Add (some)thing</button>
                    @endif
                </div>
                <div class="large-9 columns data-item">
                    Item
                </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        <div id="instruction" class="artefact_lightbox reveal-modal full" data-reveal role="dialog">
            <div class="row">
                <div class="large-3 columns" id="instruction_metadata">
                    <h2 id="modalTitle" class="data-title">Title</h2>
                    <dl class="details">
                        <dt>Added</dt>
                        <dd class="data-added">dd/mm/yy hh:mm</dd>
                        <dt>By</dt>
                        <dd class="data-author"><a href="#">Author</a></dd>
                    </dl>
                    <!-- 
                    @if (isset($user) && $user->role == 'editor')
                    <button class="big plus" data-reveal-id="new_artefact">Add (some)thing</button>
                    @endif
                    -->
                </div>
                <div class="large-9 columns data-item">
                    {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
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

        <!-- Modal overlay for new answer to the topic -->
        @if (isset($user))
        <div id="new_artefact" class="reveal-modal slide screen-right" data-reveal data-options="animation_speed: 0" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
            <div class="row">  
               <div class="large-2 show-for-large-up columns">
                   <img src="{{ asset('img/plus_plain.png') }}" alt="plus"/>
                </div>
                <div class="large-8 medium-12 columns end">
                    {!! Form::open(array('id'=>'newTopicForm', 'data-abide', 'onsubmit'=>'return validation()', 'url'=>'comment', 'method'=>'POST', 'files'=>true)) !!}
                    <h2>Add (some)thing</h2>
                    <p>add (some)thing to this topic using the form below...</p>
                    <h3 id="instruction_title" style="cursor: pointer;">Instruction</h3>
                    <div class="row" data-equalizer>
                        <div class="small-12 columns text-center panel" data-equalizer-watch id="instruction_content">
                        </div>
                    </div>
                    <h3>General information</h3>
                    <!-- een gewone input -->
                    <div class="field_title">
                        <label>Title:
                            <input type="text" required name="answer_title"/>
                        </label>
                        <small class="error">Please enter a title for the topic.</small>
                    </div>
                    <label>Tags (select 2 below):</label>
                    <div class="tag-select" id="answer_tags">
                    <small class="error" id="error_tags">Select exactly 2 existing tags.</small>
                    </div>
                    <div>
                        <label class="form-left-label" for="new-tag">+ one new tag:
                            <input id="new-tag" type="text" name="answer_new_tag" required data-abide-validator="tag"/>
                        </label>
                        <small class="error">The new tag can not be the same as the existing tags.</small>
                    </div>
                    <h3>Choose one of the following:</h3>
                    <div class="row large" data-equalizer>
                       <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_text">
                           <button class="square purple type_select" id="type_text">
                               <img src="{{ asset('img/file_text.png') }}" alt="text" />
                               text
                           </button>
                       </div>
                       <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_image">
                           <button class="square purple type_select" id="type_image">
                               <img src="{{ asset('img/file_text.png') }}" alt="image" />
                               image<br /><small>(jpg, png, gif)</small>
                           </button>
                       </div>
                        <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_video">
                           <button class="square purple type_select" id="type_video">
                            <img src="{{ asset('img/file_movie.png') }}" alt="video" />
                              video<br /><small>(youtube, vimeo)</small>
                          </button>
                       </div>
                        <div class="small-6 large-3 columns text-center end" data-equalizer-watch id="answer_button_file">
                           <button class="square purple type_select" id="type_file">
                           <img src="{{ asset('img/file_file.png') }}" alt="file" />
                           pdf
                           </button>
                       </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <small class="error answer_input">Please choose one of the file types.</small>
                        </div>
                    </div>
                    <div class="row type_input" id="answer_input_text" style="display: none;">
                            <div class="small-12 columns">
                                    <textarea required rows="5" cols="50" name="answer_text">Type your topic text here...</textarea>
                            </div>
                    </div>
                    <div class="row type_input" id="answer_input_upload" style="display: none;">
                            <div class="small-12 columns form-inline">
                                    <label for="answer_upload">upload a file:</label>
                                    <span class="field">
                                        <input type="file" id="answer_upload" name="answer_upload"/>
                                    </span>
                            </div>
                    </div>
                    <div class="row type_input" id="answer_input_or" style="display: none;">
                            <div class="small-12 columns">
                                    <strong>or</strong>
                            </div>
                    </div>
                    <div class="row type_input" id="answer_input_url" style="display: none;">
                            <div class="small-12 columns form-inline">
                                    <label for="answer_url">url:</label>
                                    <span class="field">
                               <input id="answer_url" type="text" name="answer_url"/>
                                    </span>
                            </div>
                    </div>

                    <h3>Extra information</h3>
                    <div class="form-inline">
                        <label for="attachment">document:</label>
                        <span class="field"><input type="file" id="attachment" name="answer_attachment"/></span>
                    </div>
                    <input type="hidden" name="answer_temp_type" id="answer_temp_type" />
                    <input type="hidden" name="answer_parent" id="answer_parent" />
                    <input type="submit" class="full purple" value="Create topic"/>
                    </form>
               </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>
        @endif

        <!-- Modal overlay for new instruction to the topic -->
        @if (isset($user) && $user->role == 'editor')
        <div id="new_instruction" class="reveal-modal slide screen-right" data-reveal data-options="animation_speed: 0" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
            <div class="row">  
               <div class="large-2 show-for-large-up columns">
                   <img src="{{ asset('img/plus_plain.png') }}" alt="plus"/>
                </div>
                <div class="large-8 medium-12 columns end">
                    {!! Form::open(array('id'=>'newTopicForm', 'data-abide', 'onsubmit'=>'return instruction_validation()', 'url'=>'instruction/new', 'method'=>'POST', 'files'=>true)) !!}
                    <h2>Add instruction</h2>
                    <p>add an instruction to this topic. The current instruction will be disabled.</p>
                    <h3 id="new_instruction_title" style="cursor: pointer;">Current Instruction</h3>
                    <div class="row" data-equalizer>
                        <div class="small-12 columns text-center panel" data-equalizer-watch id="new_instruction_content">
                        </div>
                    </div>
                    <h3>General information</h3>
                    <!-- een gewone input -->
                    <div class="field_title">
                        <label>Title:
                            <input type="text" required name="instruction_title"/>
                        </label>
                        <small class="error">Please enter a title for the topic.</small>
                    </div>
                    <label>Available types (check to enable):</label>
                   <div class="tag-select" id="instruction_types">
                        <div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="text"><span>Text</span></label></div>
                        <div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="image"><span>Image</span></label></div>
                        <div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="video"><span>Video</span></label></div>
                        <div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" value="file"><span>Document (pdf)</span></label></div>
                        <small class="error" id="error_types">Select at least 1 available option.</small>
                    </div>
                    <h3>Choose one of the following:</h3>
                    <div class="row large" data-equalizer>
                       <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_text">
                           <button class="square purple type_select" id="type_text">
                               <img src="{{ asset('img/file_text.png') }}" alt="text" />
                               text
                           </button>
                       </div>
                       <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_image">
                           <button class="square purple type_select" id="type_image">
                               <img src="{{ asset('img/file_text.png') }}" alt="image" />
                               image<br /><small>(jpg, png, gif)</small>
                           </button>
                       </div>
                        <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_video">
                           <button class="square purple type_select" id="type_video">
                            <img src="{{ asset('img/file_movie.png') }}" alt="video" />
                              video<br /><small>(youtube, vimeo)</small>
                          </button>
                       </div>
                        <div class="small-6 large-3 columns text-center end" data-equalizer-watch id="instruction_button_file">
                           <button class="square purple type_select" id="type_file">
                           <img src="{{ asset('img/file_file.png') }}" alt="file" />
                           pdf
                           </button>
                       </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <small class="error instruction_input">Please choose one of the file types.</small>
                        </div>
                    </div>
                    <div class="row type_input" id="instruction_input_text" style="display: none;">
                            <div class="small-12 columns">
                                    <textarea required rows="5" cols="50" name="instruction_text">Type your instruction text here...</textarea>
                            </div>
                    </div>
                    <div class="row type_input" id="instruction_input_upload" style="display: none;">
                            <div class="small-12 columns form-inline">
                                    <label for="instruction_upload">upload a file:</label>
                                    <span class="field">
                                        <input type="file" id="instruction_upload" name="instruction_upload"/>
                                    </span>
                            </div>
                    </div>
                    <div class="row type_input" id="instruction_input_or" style="display: none;">
                            <div class="small-12 columns">
                                    <strong>or</strong>
                            </div>
                    </div>
                    <div class="row type_input" id="instruction_input_url" style="display: none;">
                            <div class="small-12 columns form-inline">
                                    <label for="instruction_url">url:</label>
                                    <span class="field">
                               <input id="instruction_url" type="text" name="instruction_url"/>
                                    </span>
                            </div>
                    </div>

                    <input type="hidden" name="instruction_temp_type" id="instruction_temp_type" />
                    <input type="hidden" name="instruction_parent" id="instruction_parent" />
                    <input type="submit" class="full purple" value="Create instruction"/>
                    </form>
               </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>
        @endif
        
        {!! HTML::script('js/vendor/jquery.js') !!}
        {!! HTML::script('js/foundation.min.js') !!}
        {!! HTML::script('js/flexie.min.js') !!}
        {!! HTML::script('js/topic.js') !!}
        {!! HTML::script('js/imagesloaded.min.js') !!}
        {!! HTML::script('js/pointer-events-polyfill.js') !!}
        <script>
            var host = "{{ URL::to('/') }}";
            var newTopic;
            var newInstruction;
            $(document).foundation();
            $(document).ready(function(){
                //polyfill
                PointerEventsPolyfill.initialize({selector: 'iframe'});
                //loadInstruction({{ $artefactLeft }});
                $('[data-reveal-id]').on('click', function() {
                    switch ($(this).data('artefact')) {
                        case 'left': configAnswer(artefactLeft); break;
                        case 'right': configAnswer(artefactRight); break;
                    }
                });
                $(document).on('open.fndtn.reveal', '#new_artefact[data-reveal]', function () {
                    var modal = $(this);
                    $(document).foundation('equalizer', 'reflow');
                    if(modal.hasClass('slide')){
                        modal.animate({right:'0%'},500);
                    }
                });
                $(document).on('open.fndtn.reveal', '#new_instruction[data-reveal]', function () {
                    var modal = $(this);
                    $(document).foundation('equalizer', 'reflow');
                    if(modal.hasClass('slide')){
                        modal.animate({right:'0%'},500);
                    }
                });
                
                $('#new_artefact button.purple').click(showAnswerType); // Click on button in modal window to toggle the answer options
                $('#new_instruction button.purple').click(showInstructionType); // Click on button in modal window for new instruction
                $('#artefact_left_contents').hide();
                $('#artefact_right_contents').hide();
                showArtefactLeft({{ $artefactLeft }}, {{ isset($answerRight)? $answerRight : 0 }});

                /* ANSWER TOEVOEGEN */
                /* Deze mogen weg? */
                /*
                newTopic = {
                    open: function(){ console.log('New topic open');
                        $("#new_answer").show();
                        $("#new_answer").animate({right:'0'}, 500);
                    },
                    close: function(){
                        $("#new_answer").animate({right:'-50%'}, 500, function(){
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
                }); */
                /* INSTRUCTION TOEVOEGEN */
                //newInstruction = {
                //    open: function(){
                //        $("#new_instruction").show();
                //        $("#new_instruction").animate({right:'0'}, 500);
                //    },
                //    close: function(){
                //        $("#new_instruction").animate({right:'-50%'}, 500, function(){
                //            $("#new_instruction").hide();
                //        });
                //    }
                //}
                //$("#open_new_instruction").click(function(){
                //    newTopic.close();
                //    newInstruction.open();
                //});
                //$("#close_new_instruction").click(function(){
                //    newInstruction.close();
                //});
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

        $(document).foundation({
            abide : {
                validators: {
                    tag: function(el, required, parent){
                        var tags = [];
                        var valid = true;
                        $('#answer_tags input:checked').each(function() {
                            if ($.inArray($(this).next().text(), tags) > -1) {
                                valid = false;
                            }
                            tags.push($(this).next().text());
                        })
                        $('[data-abide-validator="tag"]').each(function(){
                            if($.inArray($(this).val(), tags) > -1){
                                valid = false;
                            }
                            tags.push($(this).val());
                        });
                        return valid;
                    },
                }
            }
        });
        </script>
    </body>
</html>
