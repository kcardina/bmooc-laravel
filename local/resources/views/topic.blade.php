<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>bMOOC LUCA School of Arts</title>
        <link rel="icon" type="img/ico" href="{{ URL::to('/') }}/img/favicon.ico">
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
        <div id="container" class="full">
            <header>
                <div class="row large">
                    <div class="small-12 columns text-right">
                        <nav class="main">
                            <ul class="inline slash">
                                <li>
                                    {!! HTML::link('#', 'help', array('help-show' => 'topic')) !!}
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
                    <div class="small-8 medium-9 large-10 columns end">
                        <button class="big information pullup space" data-reveal-id="instruction" data-help="topic" data-help-id="view_current_instruction" style="display: none;">Topic instruction</button>
                        @if (isset($user) && $user->role=="editor")
                        <button class="big plus pullup" data-reveal-id="new_instruction" data-help="topic" data-help-id="new_instruction">New instruction</button>
                        @endif
                    </div>
                </div>
            </header>

            <div class="topic">
                <div class="row fullflex">
                    <div class="small-6 columns full">
                        <div class="loader" id="artefact_left_loader">
                            {!! HTML::image(asset("img/loader_dark_big.gif"), 'loading...') !!}
                        </div>
                        <div class="artefact" id="artefact_left_contents" data-reveal-id="artefact_lightbox_left"></div>
                    </div>
                    <div class="small-6 columns full">
                        <div class="loader" id="artefact_right_loader">
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
                <div class="row buttons">
                    <div class="small-6 columns" id="artefect_left_buttons">
                        <button class="small eye space" data-reveal-id="artefact_lightbox_left" data-help="topic" data-help-id="details">Details</button>
                        @if (isset($user))
                        <button class="small plus" data-artefact="left" data-reveal-id="new_artefact" data-help="topic" data-help-id="new_artefact">Add (some)thing</button>
                        @else
                        <button class="small plus" data-artefact="left" data-reveal-id="signin" data-reveal-ajax='<?php echo url('auth/login'); ?>' data-help="topic" data-help-id="new_artefact">Add (some)thing</button>
                        @endif
                    </div>
                    <div class="small-6 columns" id="artefact_right_buttons">
                        <button class="small eye space" data-reveal-id="artefact_lightbox_right">Details</button>
                        @if (isset($user))
                        <button class="small plus" data-artefact="right" data-reveal-id="new_artefact" data-help="topic" data-help-id="new_artefact">Add (some)thing</button>
                        @else
                        <button class="small plus" data-artefact="right" data-reveal-id="signin" data-reveal-ajax='<?php echo url('auth/login'); ?>' data-help="topic" data-help-id="new_artefact">Add (some)thing</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="artefact_lightbox_left" class="artefact_lightbox reveal-modal full" data-reveal role="dialog">
            <div class="row">
                <div class="small-12 large-3 columns large-float">
                    <h2 id="modalTitle" class="data-title">Title</h2>
                    <dl class="details">
                       <div class="row">
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Added</dt>
                            <dd class="data-added">dd/mm/yy hh:mm</dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>By</dt>
                            <dd class="data-author"><a href="#">Author</a></dd>
                           </div>
                            <div class="small-6 medium-3 large-12 columns">
                            <dt>Tags</dt>
                            <dd>
                                <ul class="inline slash data-tags">
                                    <li><a href="#">tag 1</a></li>
                                    <li><a href="#">tag 2</a></li>
                                    <li><a href="#">tag 3</a></li>
                                </ul>
                            </dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Copyright</dt>
                            <dd class="data-copyright"></dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Extra information</dt>
                            <dd class="data-attachment"><a href="#" target="_new">File</a></dd>
                           </div>
                        </div>
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
                    <div class="buttons">
                        <button class="small information space space-bottom" data-reveal-id="instruction" data-help="topic" data-help-id="view_current_instruction" style="display: none;">Topic instruction</button>
                        @if (isset($user))
                        <button id="button_add_left" class="big plus" data-reveal-id="new_artefact"  data-artefact="left">Add (some)thing</button>
                        @else
                        <button id="button_add_left" class="big plus" data-reveal-id="signin" data-reveal-ajax='<?php echo url('auth/login'); ?>' data-artefact="left">Add (some)thing</button>
                        @endif
                    </div>

                </div>
                <div class="small-12 large-9 columns data-item large-float">
                    <div class="loader">
                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                    </div>
                   <div class="artefact"></div>
                </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        <div id="artefact_lightbox_right" class="artefact_lightbox reveal-modal full" data-reveal role="dialog">
            <div class="row">
                <div class="small-12 large-3 columns large-float">
                    <h2 id="modalTitle" class="data-title">Title</h2>
                    <dl class="details">
                       <div class="row">
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Added</dt>
                            <dd class="data-added">dd/mm/yy hh:mm</dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>By</dt>
                            <dd class="data-author"><a href="#">Author</a></dd>
                           </div>
                            <div class="small-6 medium-3 large-12 columns">
                            <dt>Tags</dt>
                            <dd>
                                <ul class="inline slash data-tags">
                                    <li><a href="#">tag 1</a></li>
                                    <li><a href="#">tag 2</a></li>
                                    <li><a href="#">tag 3</a></li>
                                </ul>
                            </dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Copyright</dt>
                            <dd class="data-copyright"></dd>
                           </div>
                           <div class="small-6 medium-3 large-12 columns">
                            <dt>Extra information</dt>
                            <dd class="data-attachment"><a href="#" target="_new">File</a></dd>
                           </div>
                        </div>
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
                    <div class="buttons">
                        <button class="small information space space-bottom" data-reveal-id="instruction" data-help="topic" data-help-id="view_current_instruction" style="display: none;">Topic instruction</button>
                        @if (isset($user))
                        <button id="button_add_right" class="big plus" data-reveal-id="new_artefact"  data-artefact="right">Add (some)thing</button>
                        @else
                        <button id="button_add_right" class="big plus" data-reveal-id="signin" data-reveal-ajax='<?php echo url('auth/login'); ?>' data-artefact="right">Add (some)thing</button>
                        @endif
                    </div>
                </div>
                <div class="small-12 large-9 columns data-item large-float">
                    <div class="loader">
                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                    </div>
                   <div class="artefact"></div>
                </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        <div id="instruction" class="artefact_lightbox reveal-modal half" data-reveal role="dialog">
            <div class="row">
                <div class="medium-3 columns" id="instruction_metadata">
                    <h2 id="modalTitle" class="data-title">Title</h2>
                    <dl class="details">
                        <dt>Added</dt>
                        <dd class="data-added">dd/mm/yy hh:mm</dd>
                        <dt>By</dt>
                        <dd class="data-author"><a href="#">Author</a></dd>
                        <!--
                        <dt>Accepted answer types</dt>
                        <dd class="data-answer-types"></dd>
                        -->
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

        <!-- Modal overlay for new answer to the topic -->
        @if (isset($user))
        <div id="new_artefact" class="reveal-modal slide screen-right" data-reveal data-options="animation_speed: 0" aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
            <div class="row">  
               <div class="large-2 show-for-large-up columns">
                   <img src="{{ asset('img/plus_plain.png') }}" alt="plus"/>
                </div>
                    <div class="large-8 medium-12 columns end">
                    {!! Form::open(array('id'=>'newTopicForm', 'data-abide'=>'ajax', 'url'=>'comment', 'method'=>'POST', 'files'=>true)) !!}
                    <h2>Add (some)thing</h2>
                    <fieldset>
                        <h3 id="instruction_title" style="cursor: pointer;">&#x25BC; Current instruction</h3>
                        <div class="row">
                            <div class="small-12 columns">
                                <div class="panel" id="instruction_content">
                                    <div class="loader">
                                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                                    </div>
                                   <div class="artefact"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <h3>General information</h3>
                        <!-- een gewone input -->
                        <div class="field_title">
                            <label>Title:
                                <input type="text" required name="answer_title"/>
                            </label>
                            <small class="error">Please enter a title for your addition.</small>
                        </div>
                        <label>Select two tags below:</label>
                        <div class="tag-select" id="answer_tags">
                            <small class="error" id="error_tags">Select exactly 2 existing tags.</small>
                        </div>
                        <div>
                            <label class="form-left-label" for="new-tag">Add one new tag:
                                <input id="new-tag" type="text" name="answer_new_tag" required data-abide-validator="tag_existing"/>
                            </label>
                            <small class="error">The new tag can not be empty and can not be the same as the selected tags.</small>
                        </div>
                    </fieldset>
                    <fieldset> <!-- BUTTONS: answer_button_xxx -->
                        <h3>Add text, an image, a video or a document:</h3>
                        <div class="filetype">
                           <!-- buttons -->
                            <div class="row large" data-equalizer>
                               <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_text">
                                   <button class="square purple type_select" id="type_text">
                                       <i class="fa fa-align-justify"></i>
                                       text
                                   </button>
                               </div>
                               <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_image">
                                   <button class="square purple type_select" id="type_image">
                                       <i class="fa fa-camera"></i>
                                       image<br /><small>(jpg, png, gif)</small>
                                   </button>
                               </div>
                                <div class="small-6 large-3 columns text-center" data-equalizer-watch id="answer_button_video">
                                   <button class="square purple type_select" id="type_video">
                                    <i class="fa fa-video-camera"></i>
                                      video<br /><small>(youtube, vimeo)</small>
                                  </button>
                               </div>
                                <div class="small-6 large-3 columns text-center end" data-equalizer-watch id="answer_button_file">
                                   <button class="square purple type_select" id="type_file">
                                   <i class="fa fa-file"></i>
                                   document<br /><small>(pdf)</small>
                                   </button>
                               </div>
                            </div>
                            <!-- input fields -->
                            <div class="row type_input input_textarea" id="answer_input_text" style="display: none;"> <!-- Div om text-input mogelijk te maken -->
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
                            <textarea name="answer_text" style="display:none"></textarea>
                	    </div>
                            </div>
                            <div class="row type_input input_file" id="answer_input_upload" style="display: none;"> <!-- Div om file upload mogelijk te maken -->
                                <div class="small-12 columns form-inline">
                                    <label for="answer_upload">
                                        <span class="filetype_label">Select a file to upload <small>(&lt;5MB)</small></span>:
                                        <input data-abide-validator="filesize" type="file" id="answer_upload" name="answer_upload"/>
                                    </label>
                                    <small class="error">The document is too large (> 5MB).</small>
                                </div>
                            </div>
                            <div class="row type_input input_url" id="answer_input_url" style="display: none;"> <!-- Div voor url mogelijk te maken -->
                                <div class="small-12 columns form-inline">
                                    <label for="answer_url">Upload or find a video on YouTube or Vimeo and paste the link to the video here:
                                        <input id="answer_url" type="text" name="answer_url"/>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="small-12 columns">
                                    <small class="error filetype_error">Please choose one of the file types.</small>
                                </div>
                            </div>
                            <label>Copyright, author or reference (optional):
                                <input type="text" id="copyright" name="answer_copyright"/>
                            </label>
                            <input type="hidden" class="temp_type" data-abide-validator="filetype" name="answer_temp_type" id="answer_temp_type" />
                        </div>
                    </fieldset>
                    <fieldset>
                        <h3>Extra information (optional)</h3>
                        <label>You can attach an extra jpg, png, gif or pdf file to your contribution:
                            <input type="file" data-abide-validator="filesize" id="attachment" name="answer_attachment"/>
                        </label>
                        <small class="error">The attachment is too large (> 5MB).</small>
                    </fieldset>
                    <input type="hidden" name="answer_parent" id="answer_parent" />
                    <input type="submit" class="full purple" value="Add (some)thing"/>
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
                    {!! Form::open(array('id'=>'newInstructionForm', 'data-abide'=>'ajax', 'url'=>'instruction/new', 'method'=>'POST', 'files'=>true)) !!}
                    <h2>Add instruction</h2>
                    <p>Add an instruction to this topic. The current instruction will be disabled and replaced by the new one.</p>
                    <fieldset>
                        <h3 id="new_instruction_title" style="cursor: pointer;">&#x25BC; Current instruction</h3>
                        <div class="row">
                            <div class="small-12 columns">
                                <div class="panel" id="new_instruction_content">
                                    <div class="loader">
                                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                                    </div>
                                   <div class="artefact"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                    <h3>General information</h3>
                        <!-- een gewone input -->
                        <div class="field_title">
                            <label>Title:
                                <input type="text" required name="instruction_title"/>
                            </label>
                            <small class="error">Please enter a title for the topic.</small>
                        </div>
                    </fieldset>
                    <fieldset> <!-- BUTTONS: instruction_button_xxx -->
                        <h3>Add text, an image, a video or a document as an instruction:</h3>
                        <div class="filetype">
                           <!-- buttons -->
                            <div class="row large" data-equalizer>
                               <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_text">
                                   <button class="square purple type_select" id="type_text">
                                       <i class="fa fa-align-justify"></i>
                                       text
                                   </button>
                               </div>
                               <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_image">
                                   <button class="square purple type_select" id="type_image">
                                       <i class="fa fa-camera"></i>
                                       image<br /><small>(jpg, png, gif)</small>
                                   </button>
                               </div>
                                <div class="small-6 large-3 columns text-center" data-equalizer-watch id="instruction_button_video">
                                   <button class="square purple type_select" id="type_video">
                                    <i class="fa fa-video-camera"></i>
                                      video<br /><small>(youtube, vimeo)</small>
                                  </button>
                               </div>
                                <div class="small-6 large-3 columns text-center end" data-equalizer-watch id="instruction_button_file">
                                   <button class="square purple type_select" id="type_file">
                                   <i class="fa fa-file"></i>
                                   document<br /><small>(pdf)</small>
                                   </button>
                               </div>
                            </div>
                            <!-- input fields -->
                            <div class="row type_input input_textarea" id="instruction_input_text" style="display: none;"> <!-- Div om text-input mogelijk te maken -->
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
                            <textarea name="instruction_text" style="display:none"></textarea>
                            </div>
                            </div>
                            <div class="row type_input input_file" id="instruction_input_upload" style="display: none;"> <!-- Div om file upload mogelijk te maken -->
                                <div class="small-12 columns form-inline">
                                    <label for="instruction_upload"><span class="filetype_label">Select a file to upload <small>(&lt;5MB)</small></span>:
                                        <input data-abide-validator="filesize" type="file" id="instruction_upload" name="instruction_upload"/>
                                    </label>
                                    <small class="error">The document is too large (> 5MB).</small>
                                </div>
                            </div>
                            <div class="row type_input input_url" id="instruction_input_url" style="display: none;"> <!-- Div voor url mogelijk te maken -->
                                <div class="small-12 columns form-inline">
                                    <label for="instruction_url">Upload or find a video on YouTube or Vimeo and paste the link to the video here:
                                        <input id="instruction_url" type="text" name="instruction_url"/>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="small-12 columns">
                                    <small class="error filetype_error">Please choose one of the file types.</small>
                                </div>
                            </div>
                            <input type="hidden" class="temp_type" name="instruction_temp_type" data-abide-validator="filetype" id="instruction_temp_type" />
                        </div>
                    </fieldset>
                    <fieldset>
                       <h3>Which contributions are allowed?</h3>
                        <label>(click to disable)</label>
                       <div class="tag-select" id="instruction_types">
                            <div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" checked="checked" value="text"><span>Text</span></label></div><!--
                            --><div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" checked="checked" value="image"><span>Image</span></label></div><!--
                            --><div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" checked="checked" value="video"><span>Video</span></label></div><!--
                            --><div class="tag-button purple"><label><input type="checkbox" name="instruction_types[]" checked="checked" value="file"><span>Document (pdf)</span></label></div>
                            <small class="error" id="error_types">Select at least 1 available option.</small>
                        </div>
                    </fieldset>
                    <input type="hidden" name="instruction_parent" id="instruction_parent" />
                    <input type="submit" class="full purple" value="Add instruction"/>
                    </form>
               </div>
            </div>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>
        @endif
        
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

        <div id="signin" class="reveal-modal tiny" data-reveal role="dialog" aria-hidden="true">
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        <div id="signup" class="reveal-modal tiny" data-reveal role="dialog" aria-hidden="true">
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        <div id="progress" class="reveal-modal small" data-reveal aria-hidden="true" role="dialog">
           <div class="row">
               <div class="columns small-12 text-center">
                    <div class="loader"><br /><br /><br />
                        {!! HTML::image(asset("img/loader_overlay_big.gif"), 'loading...') !!}
                    </div>
                    <p class="message">Loading...</p>
               </div>
           </div>
        </div>

        {!! HTML::script('js/vendor/jquery.js') !!}
        {!! HTML::script('js/foundation.min.js') !!}
        {!! HTML::script('js/flexie.min.js') !!}
        {!! HTML::script('js/topic.js?v=' . Version::get()) !!}
        {!! HTML::script('js/imagesloaded.min.js') !!}
        {!! HTML::script('js/pointer-events-polyfill.js') !!}
        {!! HTML::script('https://www.youtube.com/iframe_api') !!}
        {!! HTML::script('js/help.js?v=' . Version::get()) !!}
        {!! HTML::script('js/app.js?v=' . Version::get()) !!}
        {!! HTML::script('//cdn.quilljs.com/0.20.1/quill.js') !!}
        {!! HTML::script('js/jquery.form.min.js') !!}
        {!! HTML::script('js/pdf.js') !!}
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
                $(document).on('opened.fndtn.reveal', '#new_artefact[data-reveal]', function () {
                    var modal = $(this);
                    $(document).foundation('equalizer', 'reflow');
                    if(modal.hasClass('slide')){
                        modal.animate({right:'0%'},500);
                    }
                });
                $(document).on('opened.fndtn.reveal', '#new_instruction[data-reveal]', function () {
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

                // if there's a video playing: reset it
                $(document).on('closed.fndtn.reveal', '#artefact_lightbox_left[data-reveal]', function () {
                    if($("#artefact_lightbox_left iframe").length > 0){
                        var div = $("#artefact_lightbox_left .data-item");
                        div.html(div.html());
                    }
                });

                $(document).on('closed.fndtn.reveal', '#artefact_lightbox_right[data-reveal]', function () {
                    if($("#artefact_lightbox_right iframe").length > 0){
                        var div = $("#artefact_lightbox_right .data-item");
                        div.html(div.html());
                    }
                });

                $('.type_select').click(showAnswerType);

                $('#artefact_left_contents').hide();
                $('#artefact_right_contents').hide();
                showArtefactLeft({{ $artefactLeft }}, {{ isset($answerRight)? $answerRight : 0 }});

            @if (isset($user))
                // editor
                var quill_answer = new Quill('#answer_input_text .ql_editor', {
                    modules: {
                        'toolbar': { container: '#answer_input_text .ql_toolbar' },
                        'link-tooltip': true
                    },
                    theme: 'snow'
                });
            @endif
            @if (isset($user) && $user->role=="editor")
                var quill_instruction = new Quill('#instruction_input_text .ql_editor', {
                    modules: {
                        'toolbar': { container: '#instruction_input_text .ql_toolbar' },
                        'link-tooltip': true
                    },
                    theme: 'snow'
                });
            @endif

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
