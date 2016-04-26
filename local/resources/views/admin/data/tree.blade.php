@extends('admin.data.master')

@section('content')
    <div class="row">
        <div class="columns">
           <form id="topics">
                <select name="topic">
                    <option value="all">ALL TOPICS (EXPERIMENTAL!!!)</option>
                    <option disabled>──────────</option>
                   @foreach($topics as $t)
                       @if ($t->thread == $topic)
                            <option selected value="{{ $t->thread }}">{{ $t->title }}</option>
                       @else
                           <option value="{{ $t->thread }}">{{ $t->title }}</option>
                       @endif
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row" id="uitleg">
        <div class="columns small-4">
            <dl>
                <dt>Tree</dt>
                <dd>Toont de chronologische boomstructuur</dd>
            </dl>
        </div>
        <div class="columns small-4">
            <dl>
                <dt>Tags (structure)</dt>
                <dd>Linkt artefacten met dezelfde tags aan elkaar. Hoe dikker de lijn tussen artefacten, hoe meer tags er overeen komen.</dd>
                </dl>
        </div>
        <div class="columns small-4">
            <dl>
                <dt>Tags (images)</dt>
                <dd>Zie Tags (structure). Toont de artefacten ipv zwarte bollen. <strong>Experimenteel!</strong></dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="columns" id="tree">

        </div>
    </div>

   @endsection


@section('scripts')
    @parent

    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/app.js?v=' . Version::get()) !!}

    <script>
        /**
         *  Timeline
         */

        var Timeline = (function(){

            /**
             * Create a Tree.
             * @param {Tree} tree - The tree associated with the timeline
             */
            function Timeline(tree){

                var pointer = this;

                this.tree = tree;

                /* public vars & functions */
                this.formatDate = d3.time.format("%d %b %Y");

                this.max = d3.max(d3.layout.tree().nodes(this.tree.data), function(d){
                          var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                          return date;
                      });

                this.min = d3.min(d3.layout.tree().nodes(this.tree.data), function(d){
                          var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                          return date;
                      });

                this.startingValue = this.max;

                this.timeScale = d3.time.scale()
                  .domain([this.min, this.max])
                  .range([100, this.tree.width() - 100])
                  .clamp(true);

                this.brush = d3.svg.brush()
                    .x(this.timeScale)
                    .extent([this.max, this.max])
                    .on("brush", function(){
                        pointer.brushed(this);
                    });

            }

            Timeline.prototype.brushed = function(e){
                var pointer = this;

                var value = this.brush.extent()[0];

                // put the brush to a new value
                if (d3.event.sourceEvent) { // not a programmatic event
                    value = this.timeScale.invert(d3.mouse(e)[0]);
                    this.brush.extent([value, value]);
                }

                d3.select(this.tree.el).select(".handle").attr("transform", "translate(" + this.timeScale(value) + ",0)");
                d3.select(this.tree.el).select(".handle").select('text').text(this.formatDate(value));

                // show all the nodes
                this.tree.g.selectAll("g.node").attr("display", "block");
                this.tree.g.selectAll(".link").attr("display", "block");

                // hide the newest nodes
                this.tree.g.selectAll("g.node")
                    .filter(function(d) {
                        var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                        return date > pointer.brush.extent()[0];
                    }).attr("display", "none");
                this.tree.g.selectAll(".link")
                    .filter(function(d) {
                        var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.target.created_at);
                        return date > pointer.brush.extent()[0];
                    })//.attr("opacity", "0.2");
                    .attr("display", "none");

                if(this.tree.options.fit) this.tree.fit();
            }

            /**
             *  Show the timeline
             */
            Timeline.prototype.show = function(){

                var pointer = this;

                // AXIS

                // define the axis
                var axis = d3.svg.axis()
                    .scale(this.timeScale)
                    .orient("bottom")
                    .tickFormat(function(d) {
                        return pointer.formatDate(d);
                    })
                    .tickSize(0)
                    .tickPadding(12)
                    .tickValues([this.timeScale.domain()[0], this.timeScale.domain()[1]])

                // select on parent node, we don't want to scale the timeline
                this.tree.svg.append("g")
                    .attr("class", "x axis")
                    // put in middle of screen
                    .attr("transform", "translate(0," + (this.tree.height() - 25) + ")")
                    // inroduce axis
                    .call(axis)
                    .select(".domain");

                // SLIDER

                // add slider handle on parentNode, we don't want to scale it
                var slider = this.tree.svg.append("g")
                  .attr("class", "slider")
                  .call(this.brush);

                slider.selectAll(".extent,.resize")
                  .remove();

                // background for slider to make selection area bigger
                slider.select(".background")
                  .attr("height", 25)
                    .attr("transform", "translate(0," + (this.tree.height() - 25 - 12) + ")")

                // SLIDER > HANDLE
                var handle = slider.append("g")
                  .attr("class", "handle")

                handle.append("circle")
                  .attr("transform", "translate(0," + (this.tree.height() - 25) + ")")
                  .attr("r", 5)

                handle.append('text')
                  .text(this.startingValue)
                  .attr("transform", "translate(" + 0 + " ," + (this.tree.height() - 55) + ")");

                slider
                  .call(this.brush.event);
            }

            /**
             *  Hide the timeline
             */
            Timeline.prototype.hide = function(){
            }

            /**
             *  Play forward
             */
            Timeline.prototype.forward = function(){
                var pointer = this;

                d3.select(d3.select('.slider').node()).transition()
                    .ease(d3.ease("linear"))
                    .duration(function(){
                        return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((pointer.max - pointer.brush.extent()[0]) / (pointer.max - pointer.min));
                    })
                    .call(this.brush.extent([this.max, this.max]))
                    .call(this.brush.event);
            }

            /**
             *  Play backwards
             */
            Timeline.prototype.rewind = function(){
                var pointer = this;

                d3.select(d3.select('.slider').node()).transition()
                    .ease(d3.ease("linear"))
                    .duration(function(){
                        return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((pointer.brush.extent()[0] - pointer.min) / (pointer.max - pointer.min));
                    })
                    .call(this.brush.extent([this.min, this.min]))
                    .call(this.brush.event);
            }

            Timeline.prototype.stop = function(){
                d3.select(d3.select('.slider').node()).transition();
            }

            Timeline.prototype.update = function(){
                d3.select(d3.select('.slider').node()).call(this.brush.event);
            }

            /**
             *  Set to start
             */
            Timeline.prototype.toStart = function(){
            }

            /**
             *  Set to end
             */
            Timeline.prototype.toEnd = function(){
            }

            return Timeline;

        })();

    </script>
    <script>
        /**
         * EXTEND TREE
         */

        Tree.prototype.renderTags = function(){

            var nodes = JSON.parse('{!! addslashes(json_encode($list)) !!}');
            var tags = JSON.parse('{!! addslashes(json_encode($tags)) !!}');

            var force = d3.layout.force()
                .linkDistance(Tree.IMAGE_SIZE*2.5); // IMAGE_SIZE

            var links = [];
            var edges = [];
            var tagslist = [];

            // maak een lijst met tags
            tags.forEach(function(e){
                if ($.inArray(e.tag_id, tagslist) < 0) tagslist.push(e.tag_id);
            });

            // lijst voor elke tag de artefacten op met die tag
            tagslist.forEach(function(i) {
                var l = tags.filter(function(n) {
                    return n.tag_id === i;
                });

                // maak een source-target array
                for(i = 0; i < l.length; i++){
                    for(j = i+1; j < l.length; j++){
                        var st = {source: l[i].artefact_id, target: l[j].artefact_id, value: 1};

                        // voeg toe aan source target array. if exists: value++
                        var exists = links.filter(function(n) {
                            return n.source == st.source && n.target == st.target
                        });

                        if(exists.length > 0){
                            var index = links.indexOf(exists[0]);
                            var st_ = links[index];
                            st_.value++;

                            links[index] = st_;
                        } else {
                            links.push(st);
                        }
                    }
                }
            });

            // gebruik de index in de array ipv id voor Force layout
            links.forEach(function(e) {
                var sourceNode = nodes.indexOf(nodes.filter(function(n) { return n.id === e.source; })[0]);

                var targetNode = nodes.indexOf(nodes.filter(function(n) { return n.id === e.target; })[0]);

                edges.push({source: sourceNode, target: targetNode, value: e.value});
            });

            force.nodes(nodes)
                .links(edges)
                .on("start", start)
                .start();

            // declare the links
            var link = this.g.selectAll(".link")
                .data(edges);

            // enter the links
            link.enter().append("line")
                .attr("class", "link")
                .attr("stroke", "#878787")
                .attr("stroke-width", 1)
                .attr("opacity", function(d){
                    return 0.33 * d.value;
                });

            // declare the nodes
            var node = this.g.selectAll(".node")
              .data(nodes);

            this.drawNodes(node);

            node.call(force.drag);

            node.append("title")
                .text(function(d) { return d.title; });

            function start(){

                var ticksPerRender = 3;

                requestAnimationFrame(function render() {

                    for (var i = 0; i < ticksPerRender; i++) {
                        force.tick();
                    }

                    link.attr("x1", function(d) { return d.source.x; })
                        .attr("y1", function(d) { return d.source.y; })
                        .attr("x2", function(d) { return d.target.x; })
                        .attr("y2", function(d) { return d.target.y; });

                    node.attr("transform", function(d){
                        return "translate(" + d.x + "," + d.y + ")";
                    });

                    if (force.alpha() > 0) {
                        requestAnimationFrame(render);
                    }

                });
            }

        }
    </script>

    <script>

        $('#tree').height($(document).height() - $('header').height() - $('nav').height() - $('form').height() - $("#uitleg").height());

        var data = JSON.parse('{!! addslashes(json_encode($tree)) !!}');

        var tree = new Tree($('#tree').get(0), data, {
            interactive: true
        });
        var timeline = new Timeline(tree);
        tree.render("tree");
        timeline.show();

        // main buttons
        var controls = d3.select("#tree")
            .append("div")
            .attr("class", "main_controls");

        var controls_tree = controls
            .append("button")
            .text("TREE")
            .attr("class", "purple active")
            .on("click", function(){
                tree.options.interactive = true;
                tree.options.background = true;
                tree.options.showImages = true;
                tree.options.fit = false;
                tree.render("tree");
            });

        var controls_cluster = controls
            .append("button")
            .text("TAGS (structure)")
            .attr("class", "purple")
            .on("click", function(){
                tree.options.interactive = false;
                tree.options.background = false;
                tree.options.showImages = false;
                tree.options.fit = false;
                tree.render("tags");
            });

        var controls_cluster_img = controls
            .append("button")
            .text("TAGS (images)")
            .attr("class", "purple")
            .on("click", function(){
                tree.options.interactive = false;
                tree.options.background = false;
                tree.options.showImages = true;
                tree.options.fit = false;
                tree.render("tags");
            });

        $(".main_controls button").on("click", function(){
            $(".main_controls button").removeClass("active");
            $(this).addClass("active");
            timeline.update();
        });

        // slider buttons
        var timeline_controls = d3.select("#tree")
            .append("div")
            .attr("class", "timeline_controls");

        var controls_rewind = timeline_controls
            .append("button")
            .text("\u23EA\uFE0E")
            .attr("class", "purple")
            .on("click", function(){
                controls_forward.classed("active", false);
                controls_stop.classed("active", false);
                controls_rewind.classed("active", true);
                timeline.rewind();
            });

        var controls_stop = timeline_controls
            .append("button")
            .text("\u25FC\uFE0E")
            .attr("class", "purple active")
            .on("click", function(){
                controls_forward.classed("active", false);
                controls_rewind.classed("active", false);
                controls_stop.classed("active", true);
                timeline.stop();
            });

        var controls_forward = timeline_controls
            .append("button")
            .text("\u23E9\uFE0E")
            .attr("class", "purple")
            .on("click", function(){
                controls_forward.classed("active", true);
                controls_stop.classed("active", false);
                controls_rewind.classed("active", false);
                timeline.forward();
            });

    </script>

@endsection
