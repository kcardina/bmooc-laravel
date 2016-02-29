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

        .vis{
            width: 100%;
            height: 50vh;
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
           <div class="small-12 columns vis"></div>
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
    {!! HTML::script('js/d3plus.min.js') !!}
    <script>
		var host = "{{ URL::to('/') }}";
		$(document).foundation();
		$(document).ready(function(){

            $.getJSON(host + '/json/topic/24/answers', function(data) {
                var tree = new Tree('.datavis .columns', data);
                tree.draw();
                tree.resize();
            });
        });

        var Tree = (function(){

            var IMAGE_SIZE = 100;
            var MARGIN = {
                top: IMAGE_SIZE/2,
                right: IMAGE_SIZE/2,
                bottom: IMAGE_SIZE/2,
                left: IMAGE_SIZE/2
            };
            var TEXTBOUNDS = {
                width: IMAGE_SIZE,
                height: IMAGE_SIZE,
                resize: true
            }

            function Tree(el, data){
                this.data = data;
                this.el = el;
                this.width = document.querySelectorAll(el)[0].offsetWidth;
                this.height = document.querySelectorAll(el)[0].offsetHeight;
                this.tree = d3.layout.tree()
                    .nodeSize([IMAGE_SIZE, IMAGE_SIZE]);
                this.diagonal = d3.svg.diagonal()
                    .projection(function(d) { return [d.y, d.x]; });
                this.svg = d3.select(this.el).append("svg")
                    .attr("width", this.width)
                    .attr("height", this.height)
                    .append("g");
                this.g = this.svg.append("g");
                console.log(this.height);
            }

            Tree.prototype.resize = function(){
                var t = [0,0],
                    s = 1,
                    w = this.g.node().getBBox().width,
                    h = this.g.node().getBBox().height;

                if(w > this.width) s = this.width/w;
                if(h > this.height && this.height/h < s) s = this.height/h;
                t = [((this.width-w*s)/2)/s, -this.g.node().getBBox().y + (this.height-h*s)/2];

                d3.select(this.g.node().parentNode).attr("transform", "scale(" + s + ")");
                this.g.attr("transform", "translate(" + t + ")");
            }

            Tree.prototype.draw = function(){

                // Compute the new tree layout.
                var nodes = this.tree.nodes(this.data);//.reverse()
                var links = this.tree.links(nodes);

                // horizontal spacing of the nodes (depth of the node * x)
                nodes.forEach(function(d) { d.y = d.depth * (IMAGE_SIZE + IMAGE_SIZE/10) });

                // Declare the nodes.
                var node = this.g.selectAll("g.node")
                    .data(nodes);


                // Enter the nodes.
                var nodeEnter = node.enter().append("g")
                    .attr("class", "node")
                    .attr("transform", function(d) {
                        return "translate(" + d.y + "," + d.x + ")";
                    });

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
                    .attr('y', -IMAGE_SIZE/2)
                    .attr('width', IMAGE_SIZE)
                    .attr('height', IMAGE_SIZE);

                //text
                nodeEnter.filter(function(d) { return d.contents })
                    .append("rect")
                    .attr('width', IMAGE_SIZE)
                    .attr('height', IMAGE_SIZE)
                    .attr('y', -IMAGE_SIZE/2);
                nodeEnter.filter(function(d) { return d.contents })
                    .append("text")
                    .attr('y', -IMAGE_SIZE/2)
                    .text(function(d) { return d.title; })
                    .each(function(d){
                        d3plus.textwrap()
                            .config(TEXTBOUNDS)
                            .valign('middle')
                            .align('center')
                            .container(d3.select(this))
                            .draw();
                    });

                // Declare the links
                var link = this.g.selectAll("path.link")
                .data(links, function(d) { return d.target.id; });

                // Enter the links.
                link.enter().insert("path", "g")
                    .attr("class", "link")
                    .attr("d", this.diagonal);
            }

            return Tree;

        })();
/*
        var imageSize = 100;
        var margin = {
            top: imageSize/2,
            right: imageSize/2,
            bottom: imageSize/2,
            left: imageSize/2
        };
        var width = 1000;
        var height = 300;
        var textbounds = {
          width: imageSize,
          height: imageSize,
          resize: true
        }


        //tree
        var tree = d3.layout.tree()
            .nodeSize([imageSize, imageSize]);

        // to draw a smooth curved line between points
        var diagonal = d3.svg.diagonal()
            .projection(function(d) { return [d.y, d.x]; });

        var zoom = d3.behavior.zoom()
            .scaleExtent([0.1,1])
            .on("zoom", zoomed);

        var svg = d3.select(".datavis .columns").append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .append("g")
                    //.attr("transform", "translate(" + width/2 + "," + height/2 +  ")")
                    //.call(zoom)

        var g = svg.append("g");

        function zoomed() {
            var t = d3.event.translate,
                s = d3.event.scale,
                w = g.node().getBoundingClientRect().width,
                h = g.node().getBoundingClientRect().height;

            zoom.translate(t);
            g.attr("transform", "translate(" + t + ")scale(" + s + ")");
        }

        function resize(){
            var t = [0,0],
                s = 1,
                w = g.node().getBBox().width,
                h = g.node().getBBox().height;
            console.log(g.node().parentNode.getBoundingClientRect());
            console.log(g.node().getBoundingClientRect());
            console.log(g.node().getBBox());


            if(w > width) s = width/w;
            if(h > height && height/h < s) s = height/h;
            t = [((width-w*s)/2)/s, -g.node().getBBox().y + (height-h*s)/2];

            d3.select(g.node().parentNode).attr("transform", "scale(" + s + ")");
            g.attr("transform", "translate(" + t + ")");
        }

        function drawTree(source) {

          // Compute the new tree layout.
          var nodes = tree.nodes(source);//.reverse()
          var links = tree.links(nodes);

          // horizontal spacing of the nodes (depth of the node * x)
          nodes.forEach(function(d) { d.y = d.depth * (imageSize + imageSize/10) });

          // Declare the nodes.
          var node = g.selectAll("g.node")
           .data(nodes);


          // Enter the nodes.
          var nodeEnter = node.enter().append("g")
           .attr("class", "node")
           .attr("transform", function(d) {
            return "translate(" + d.y + "," + d.x + ")"; });

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
          var link = g.selectAll("path.link")
           .data(links, function(d) { return d.target.id; });

          // Enter the links.
          link.enter().insert("path", "g")
           .attr("class", "link")
           .attr("d", diagonal);
        }*/
    </script>
  </body>
</html>
