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

    <style>
        table, table tr, table tr td{
            border-collapse: collapse;
            vertical-align: top;
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
            border: none;
            background: none;
            color: #ffffff;
        }

        table td{
            height: 2rem;
        }

        table > tbody > tr:before{
            content: "\2198 ";
            /* content: "\21B3 "; */
            color: #878787;
            font-family: arial, sans-serif;
            font-size: 1.2rem;
        }

        table:first-child > tbody > tr:before{
            content: "\2192 ";
            color: #878787;
            font-family: arial, sans-serif;
            font-size: 1.2rem;
        }

        article > table:first-child > tbody > tr:before{
            content: none;
        }

        span[data-answers]{
            border-radius: 50%;
            display: block;
            text-align: center;
            height: 1rem;
            width: 1rem;
            font-weight: 400;
            border: 1px solid transparent;
            /* background-color: hsl(123, 80%, 45%); */
            /* border-color: hsl(123, 80%, 45%); */
            /* padding-top: 0.4rem; */
            /* color: hsl(123, 80%, 45%); */
            /* margin-top: -0.5rem */
            margin-top: 0.1rem;
        }

        article{
            border-bottom: 1px dotted white;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
    </style>

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

    <div class="datavis2">
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
                console.log(data);
                
                var html = "";
                
                function recurseList(d){
                    html += "<ul>";
                    html += "<li>" + d.id;
                    if(d.answers.length > 0){
                        $.each(d.answers, function(index, value){
                            recurseList(value);
                        });
                    }
                    html += "</li></ul>";
                }
                
                function recurseTable(d){
                    html += "<table><tr>";
                    html += "<td>" + d.id + "</td>";
                    if(d.answers.length > 0){
                        html += "<td>";
                        $.each(d.answers, function(index, value){
                            recurseTable(value);
                        });
                        html += "</td>";
                    }
                    html += "</tr></table>";
                }

                function recurseTableHeatmap(d){
                    html += "<table><tr>";
                    html += "<td><span data-answers=\"" + d.answers.length + "\">" + d.id + "<span></td>";
                    if(d.answers.length > 0){
                        html += "<td>";
                        $.each(d.answers, function(index, value){
                            recurseTableHeatmap(value);
                        });
                        html += "</td>";
                    }
                    html += "</tr></table>";
                }
                
                function recurseTableHeatmapAnswers(d){
                    html += "<table><tr>";
                    html += "<td><span data-answers=\"" + d.answers.length + "\">" + d.answers.length + "<span></td>";
                    if(d.answers.length > 0){
                        html += "<td>";
                        $.each(d.answers, function(index, value){
                            recurseTableHeatmapAnswers(value);
                        });
                        html += "</td>";
                    }
                    html += "</tr></table>";
                }

                function recurseTableHeatmapAnswersCleaned(d){
                    if(d.answers.length > 0){
                        html += "<table><tr>";
                        html += "<td><span data-answers=\"" + d.answers.length + "\">" + d.answers.length + "<span></td>";
                        html += "<td>";
                        $.each(d.answers, function(index, value){
                            recurseTableHeatmapAnswersCleaned(value);
                        });
                        html += "</td>";
                        html += "</tr></table>";
                    }
                }
                
                function recurseTableHeatmapCleaned(d){
                    if(d.answers.length > 0){
                        html += "<table><tr>";
                        html += "<td><span data-answers=\"" + d.answers.length + "\">&nbsp;<span></td>";
                        html += "<td>";
                        $.each(d.answers, function(index, value){
                            recurseTableHeatmapCleaned(value);
                        });
                        html += "</td>";
                        html += "</tr></table>";
                    }
                }
                
                html += "<article>"
                recurseList(data);
                html += "</article><article>";
                recurseTable(data);
                html += "</article><article>";
                recurseTableHeatmap(data);
                html += "</article><article>";
                recurseTableHeatmapAnswers(data);
                html += "</article><article>";
                recurseTableHeatmapAnswersCleaned(data);
                html += "</article><article>";
                recurseTableHeatmapCleaned(data);
                html += "</article>";

                div.html(html);
                
                /* Style the heatmap */
                // get the heighest value of data-answers
                var e = 'span[data-answers]';
                var max = minMax(e).max;

                var SIZE = false;
                var COLOR = true;
                var BORDER = false;

                $(e).each(function(){
                    if(COLOR){
                        $(this).css('background-color', 'hsla(123, 80%, 45%, ' + 1 / max * $(this).attr('data-answers') + ')');
                    }
                    if(SIZE){
                        $(this).css('width', 2 / max * $(this).attr('data-answers') + 'rem');
                        $(this).css('height', 2 / max * $(this).attr('data-answers') + 'rem');
                    }
                    if(BORDER){
                        $(this).css('border-color', 'hsla(123, 80%, 45%, ' + 1 / max * $(this).attr('data-answers') + ')');
                    }
                });

                //positioning
                $(e).each(function(){
                    $(this).css('margin-top', '-' + $(this).height()/2);
                });

                /* helper function */
                function minMax(selector) {
                  var min=null, max=null;
                  $(selector).each(function() {
                    var i = parseInt($(this).attr('data-answers') , 10);
                    if ((min===null) || (i < min)) { min = i; }
                    if ((max===null) || (i > max)) { max = i; }
                  });
                  return {min:min, max:max};
                }
                
            });
        });
    </script>
  </body>
</html>
