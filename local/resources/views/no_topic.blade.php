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
                    </div>
                </div>
            </header>

            <div class="topic">
                <div class="row fullflex">
                    <div class="small-6 columns full" style="overflow-y: hidden">
                        <div class="artefact loader" id="artefact_left_loader">
                            <h3>Topic not found</h3>
                            <p>We are sorry, but the topic requested does not exist. Please, go back to the index page and select one the topic shown.</p>
                        </div>
                        
                    </div>
                    <div class="small-6 columns full" style="overflow-y: hidden">
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

    </body>
</html>
