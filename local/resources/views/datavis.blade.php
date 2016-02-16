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
        svg {
            border: 1px dotted white;
        }

        .node circle {
           fill: #fff;
           stroke: steelblue;
           stroke-width: 3px;
         }

        .node rect{
            fill: #080808;
            stroke: none;
        }

        .node:hover{
            cursor:pointer;
        }

         .node text {
             fill: #fff;
        }

         .link {
           fill: none;
           stroke: #333;
           stroke-width: 2px;
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
    {!! HTML::script('//d3js.org/d3.v3.min.js') !!}
    {!! HTML::script('js/d3.textwrap.js') !!}
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){

            $.getJSON(host + '/json/topic/24/answers', function(data) {
                var div = $(".datavis .columns");

                // start building from the start
                update1(data);
                update2(data);
                update3(data);
                update4(data);
            });
        });

        var imageSize = 50;
        var margin = {
            top: imageSize/2,
            right: imageSize/2,
            bottom: imageSize/2,
            left: imageSize/2
        };
        var width = 960 - margin.right - margin.left;
        var height = 500 - margin.top - margin.bottom;
        var textbounds = {
          width: imageSize,
          height: imageSize,
          resize: true
        }


        //tree
        var tree = d3.layout.tree()
            //.size([height, width])
            //.separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) });
            .nodeSize([imageSize, imageSize]);
        //or cluster
        /*var tree = d3.layout.cluster()
            .size([height, width]);*/

        // to draw a smooth curved line between points
        var diagonal = d3.svg.diagonal()
            .projection(function(d) { return [d.y, d.x]; });

        var zoomListener = d3.behavior.zoom()
            .scaleExtent([0,3])
            .on("zoom", zoomHandler);

        var n = 4;
        var svg = [];

        // add svg and create a group object
        for(i = 0; i < n; i++){
            svg.push(
                d3.select(".datavis .columns").append("svg")
                    .attr("width", width + margin.right + margin.left)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", "translate(" + margin.left + "," + height/2 +  ")")
                    .call(zoomListener)
            );
        }

        function redraw(i) {
          //console.log("here", d3.event.translate, d3.event.scale);
          svg[i].attr("transform",
              "translate(" + d3.event.translate + ")"
              + " scale(" + d3.event.scale + ")");
        }

        function zoomHandler() {
            svg[3].attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
        }

        function update1(source) {

          // Compute the new tree layout.
          var nodes = tree.nodes(source);//.reverse()
          var links = tree.links(nodes);

          // horizontal spacing of the nodes (depth of the node * x)
          nodes.forEach(function(d) { d.y = d.depth * imageSize; });

          // Declare the nodes.
          var node = svg[0].selectAll("g.node")
           .data(nodes);

          // Enter the nodes.
          var nodeEnter = node.enter().append("g")
           .attr("class", "node")
           .attr("transform", function(d) {
            return "translate(" + d.y + "," + d.x + ")"; });

          // circle
          nodeEnter.append("circle")
           .attr("r", 10)
           .style("fill", "#fff");

            //img
            /*
            nodeEnter.append("image")
                .attr("xlink:href","img.png")
                .attr('width', 100)
                .attr('height', 100);*/

          // text
          nodeEnter.append("text")
           .text(function(d) { return d.id; })
           .style("fill-opacity", 1);

          // Declare the links
          var link = svg[0].selectAll("path.link")
           .data(links, function(d) { return d.target.id; });

          // Enter the links.
          link.enter().insert("path", "g")
           .attr("class", "link")
           .attr("d", diagonal);

        }

        function update2(source) {

          // Compute the new tree layout.
          var nodes = tree.nodes(source);//.reverse()
          var links = tree.links(nodes);

          // horizontal spacing of the nodes (depth of the node * x)
          nodes.forEach(function(d) { d.y = d.depth * 100; });

          // Declare the nodes.
          var node = svg[1].selectAll("g.node")
           .data(nodes);

          // Enter the nodes.
          var nodeEnter = node.enter().append("g")
           .attr("class", "node")
           .attr("transform", function(d) {
            return "translate(" + d.y + "," + d.x + ")"; });

          // Declare the links
          var link = svg[1].selectAll("path.link")
           .data(links, function(d) { return d.target.id; });

          // Enter the links.
          link.enter().insert("path", "g")
           .attr("class", "link")
           .attr("d", diagonal);

        }

        function update3(source) {

          // Compute the new tree layout.
          var nodes = tree.nodes(source);//.reverse()
          var links = tree.links(nodes);

          // horizontal spacing of the nodes (depth of the node * x)
          nodes.forEach(function(d) { d.y = d.depth * 200; });

          // Declare the nodes.
          var node = svg[2].selectAll("g.node")
           .data(nodes);

          // Enter the nodes.
          var nodeEnter = node.enter().append("g")
           .attr("class", "node")
           .attr("transform", function(d) {
            return "translate(" + d.y + "," + d.x + ")"; });

            //img
            nodeEnter.append(function(d) { return getThumb(d) } );
                /*.attr("xlink:href", function(d) { return getThumb(d) })
                .attr('x', -50)
                .attr('y', -50)
                .attr('width', 100)
                .attr('height', 100);*/

          // Declare the links
          var link = svg[2].selectAll("path.link")
           .data(links, function(d) { return d.target.id; });

          // Enter the links.
          link.enter().insert("path", "g")
           .attr("class", "link")
           .attr("d", diagonal);

        }

        function getThumb(d){
            var e;
            if(d.url != null){
                e = document.createElement("image");
                e.setAttribute("href", "/uploads/" + d.url);
                e.setAttribute('x', -25);
                e.setAttribute('y', -25);
                e.setAttribute('width', 50)
                e.setAttribute('height', 50);
            } else if (d.contents != null){
                e = document.createElement("text");
                e.innerHTML = d.contents;
            }
            return e;
        }

        function update4(source) {

          // Compute the new tree layout.
          var nodes = tree.nodes(source);//.reverse()
          var links = tree.links(nodes);

          // horizontal spacing of the nodes (depth of the node * x)
          nodes.forEach(function(d) { d.y = d.depth * (imageSize + imageSize/10) });

          // Declare the nodes.
          var node = svg[3].selectAll("g.node")
           .data(nodes);


          // Enter the nodes.
          var nodeEnter = node.enter().append("g")
           .attr("class", "node")
           .attr("transform", function(d) {
            return "translate(" + d.y + "," + d.x + ")"; });

            /*
            // border
            nodeEnter.append("rect")
                .attr('width', imageSize)
                .attr('height', imageSize)
                .attr('y', -imageSize/2)
            */

            //img
            nodeEnter.filter(function(d) { return d.url; }).append("image")
                .attr("xlink:href", function(d) {
                    if (d.url.indexOf('//') > -1 ) {
                        if(d.url.indexOf('youtu') > -1) {
                            var thumbnail = d.url.replace('www.youtube.com/embed', 'img.youtube.com/vi');
                            return thumbnail +'/0.jpg';
                        } else {
                            return d.url
                        }
                    } else {
                        return "/uploads/" + d.url
                    }
                })
                .attr('y', -imageSize/2)
                .attr('width', imageSize)
                .attr('height', imageSize);

            //text
            nodeEnter.filter(function(d) { return d.contents })
                .append("rect")
                .attr('width', imageSize)
                .attr('height', imageSize)
                .attr('y', -imageSize/2);
            nodeEnter.filter(function(d) { return d.contents })
                .append("text")
                .attr('y', -imageSize/2)
                .text(function(d) { return d.title; })
                .each(function(d){
                    d3plus.textwrap()
                        .config(textbounds)
                        .valign('middle')
                        .align('center')
                        .container(d3.select(this))
                        .draw();
                });

            // Declare the links
          var link = svg[3].selectAll("path.link")
           .data(links, function(d) { return d.target.id; });

          // Enter the links.
          link.enter().insert("path", "g")
           .attr("class", "link")
           .attr("d", diagonal);
        }
    </script>
  </body>
</html>
