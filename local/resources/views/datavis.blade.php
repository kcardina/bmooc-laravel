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
            </div>
		</header>

   <div class="datavis">
       <div class="row">
           <div class="small-12 columns"></div>
       </div>
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
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){
            $.getJSON(host + '/json/topic/24/answers', function(data) {
                var div = $(".datavis .columns");
                
                /*var html = "<table><tr><td>";
                
                function recurse(d){
                    console.log(d);
                    html += "<td>";
                    $.each(d, function(index, value){
                        html += "<tr>" + value.id + "</tr>";
                        if(value.answers.length > 0){
                            recurse(value.answers);
                        }
                    });
                    html += "</td>";
                }
                
                recurse(data.answers);
                html += "<td></tr></table>";*/
                
                var html = "<table><tr><td>";
                
                function recurse(d){
                    console.log(d);
                    var i = 0;
                    $.each(d, function(index, value){
                        i++
                        if(value.answers.length > 0){
                            recurse(value.answers);
                        } else {
                            html += "<br />";
                        }
                    });
                    html += i;
                }
                
                recurse(data.answers);
                html += "<td></tr></table>";
                
                div.append(html);
                
            });
        });
    </script>
  </body>
</html>
