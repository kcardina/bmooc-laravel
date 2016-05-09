<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - bMOOC - LUCA School of Arts</title>
    <link rel="icon" type="img/ico" href="img/favicon.ico">

    {{-- FONTS --}}
    {!! HTML::style('https://fonts.googleapis.com/css?family=Muli:400,300') !!}

    {{-- STYLESHEETS --}}
    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/app.css?v=' . Version::get()) !!}

    {{-- SCRIPTS --}}
    {!! HTML::script('js/vendor/modernizr.js') !!}
  </head>
	<body>
        {{-- JS: Google Analytics --}}
	    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-71362622-1', 'auto');
          ga('send', 'pageview');
        </script>

        {{-- CSS: Font Awesome --}}
        <script type="text/javascript">
            (function() {
                var css = document.createElement('link');
                css.href = '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css';
                css.rel = 'stylesheet';
                css.type = 'text/css';
                document.getElementsByTagName('head')[0].appendChild(css);
            })();
        </script>

        {{-- CSS: QuilJS --}}
        <script type="text/javascript">
            (function() {
                var css = document.createElement('link');
                css.href = '//cdn.quilljs.com/0.20.1/quill.snow.css';
                css.rel = 'stylesheet';
                css.type = 'text/css';
                document.getElementsByTagName('head')[0].appendChild(css);
            })();
        </script>

        <header>
            <div class="row large">
			    <div class="small-12 columns text-right">
                    <nav class="main">
                        <ul class="inline slash">
                           <li>
                                {!! HTML::link('#', 'help', array('help-show' => 'index')) !!}
                            </li>
                            <li>
                                {!! HTML::link('#', 'about', array('data-reveal-id' => 'about')) !!}
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
                    @yield('header_actions')
                </div>
                <div class="large-7 columns" data-help="index" data-help-id="search">
                    @yield('header_search')
                </div>
            </div>
        </header>

        <div class="container">
            @yield('content')
        </div>

        {{-- MODALS --}}
        {{-- ABOUT --}}
        <div id="about" class="reveal-modal small" data-reveal aria-labelledby="about_title" aria-hidden="true" role="dialog">
            <h2 id="about_title">bMOOC</h2>
            <h3>A Massive, Open, Online Course to think with eyes and hands</h3>

            <p>The point of departure and finality of <strong>b</strong>MOOC is that, whether you are a teacher or a student, you are intrigued by 'images'.</p>

            <p>The structure of bMOOC is simple: the course consists of topics. A topic is a collection of online artefacts that are placed next to each other. A topic opens a space for gathering. The first question is: how to relate to this topic?</p>

            <p>Topics may have specific instructions. They do not determine the contribution, but ask the contributor to disclose the gaze and to become attentive for (some)thing(s).</p>

            <p>Login/register in order to join. Feel free to contribute to any topic. Click {!! HTML::link('#', 'help', array('class'=>'emphasis', 'help-show' => 'index')) !!} for assistance and {!! HTML::link('#', 'about', array('class'=>'emphasis', 'data-reveal-id' => 'about')) !!} for more information.</p>

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

        {{-- FEEDBACK --}}
        <div id="feedback" class="reveal-modal small" data-reveal aria-labelledby="feedback_title" aria-hidden="true" role="dialog">
            <h2 id="feedback_title">Feedback</h2>
            <p>Remarks, problems or suggestions? Please fill in the form below.</p>
            @include('forms.feedback')
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
        </div>

        {{-- SCRIPTS --}}
        {!! HTML::script('js/vendor/jquery.js') !!}
        {!! HTML::script('js/foundation.min.js') !!}
        {!! HTML::script('js/app.js?v=' . Version::get()) !!}
        {!! HTML::script('js/help.js?v=' . Version::get()) !!}

        <script>
            $(document).foundation();
        </script>

        @yield('scripts')

    </body>
</html>
