@extends('admin.data.master')

@section('content')
    @parent

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
         * TREE
         */

        Tree.prototype.timeline = function(){

            var pointer = this;

            formatDate = d3.time.format("%d %b %Y");

            var max = d3.max(this.tree.nodes(this.data), function(d){
                      var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                      return date;
                  });
            var min = d3.min(this.tree.nodes(this.data), function(d){
                      var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                      return date;
                  })

            // scale function
            var timeScale = d3.time.scale()
              .domain([min, max])
              .range([100, this.width() - 100])
              .clamp(true);

            // initial value (max date)
            var startingValue = d3.max(this.tree.nodes(this.data), function(d){
                  var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                  return date;
              })

            // defines brush
            var brush = d3.svg.brush()
                .x(timeScale)
                .extent([max, max])
                .on("brush", brushed)

            var axis = d3.svg.axis()
                .scale(timeScale)
                .orient("bottom")
                .tickFormat(function(d) {
                    return formatDate(d);
                })
                .tickSize(0)
                .tickPadding(12)
                .tickValues([timeScale.domain()[0], timeScale.domain()[1]])

            // select on parent node, we don't want to scale the timeline
            d3.select(this.svg.node().parentNode).append("g")
                .attr("class", "x axis")
                // put in middle of screen
                .attr("transform", "translate(0," + (this.height() - 25) + ")")
                // inroduce axis
                .call(axis)
                .select(".domain");

            // add slider handle on parentNode, we don't want to scale it
            var slider = d3.select(this.svg.node().parentNode).append("g")
              .attr("class", "slider")
              .call(brush);

            slider.selectAll(".extent,.resize")
              .remove();

            // background for slider to make selection area bigger
            slider.select(".background")
              .attr("height", 25)
                .attr("transform", "translate(0," + (this.height() - 25 - 12) + ")")

            // HANDLE
            var handle = slider.append("g")
              .attr("class", "handle")

            handle.append("circle")
              .attr("transform", "translate(0," + (this.height() - 25) + ")")
              .attr("r", 5)

            handle.append('text')
              .text(startingValue)
              .attr("transform", "translate(" + 0 + " ," + (this.height() - 55) + ")");

            slider
              .call(brush.event)

            function brushed() {
                var value = brush.extent()[0];

                // put the brush to a new value
                if (d3.event.sourceEvent) { // not a programmatic event
                    value = timeScale.invert(d3.mouse(this)[0]);
                    brush.extent([value, value]);
                }

                handle.attr("transform", "translate(" + timeScale(value) + ",0)");
                handle.select('text').text(formatDate(value));


                // show all the nodes
                d3.select(this.parentNode).selectAll("g.node").attr("display", "block");
                d3.select(this.parentNode).selectAll(".link").attr("display", "block");
                // hide the newest nodes
                d3.select(this.parentNode).selectAll("g.node")
                    .filter(function(d) {
                        var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                        return date > brush.extent()[0];
                    }).attr("display", "none");
                d3.select(this.parentNode).selectAll(".link")
                    .filter(function(d) {
                        var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.target.created_at);
                        return date > brush.extent()[0];
                    })//.attr("opacity", "0.2");
                    .attr("display", "none");
                pointer.fit();
            }

            // play buttons
            var controls = d3.select("#tree")
                .append("div")
                .attr("class", "timeline_controls");

            var controls_rewind = controls
                .append("button")
                .text("\u23EA\uFE0E")
                .attr("class", "purple")
                .on("click", rewind);

            var controls_forward = controls
                .append("button")
                .text("\u23E9\uFE0E")
                .attr("class", "purple")
                .on("click", forward);


            function rewind(){
                controls_forward.classed("active", false);
                controls_rewind.classed("active", true);
                d3.select(d3.select('.slider').node()).transition()
                    .ease(d3.ease("linear"))
                    .duration(function(){
                        return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((brush.extent()[0] - min) / (max - min));
                    })
                    .call(brush.extent([min, min]))
                    .call(brush.event);
            }

            function forward(){
                controls_rewind.classed("active", false);
                controls_forward.classed("active", true);
                d3.select(d3.select('.slider').node()).transition()
                    .ease(d3.ease("linear"))
                    .duration(function(){

                        return (5000 + 200 * d3.select(this.parentNode).selectAll("g.node").size()) * Math.abs((max - brush.extent()[0]) / (max - min));
                    })
                    .call(brush.extent([max, max]))
                    .call(brush.event);
            }

        }

        Tree.prototype.drawCluster = function(){

            var nodes = JSON.parse('{!! addslashes(json_encode($list)) !!}');
            var tags = JSON.parse('{!! addslashes(json_encode($tags)) !!}');

            var force = d3.layout.force()
                .linkDistance(200); // IMAGE_SIZE

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

            this.drawNodes(node, {background: 'none'});

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

        $('#tree').height($(document).height() - $('header').height() - $('nav').height() - $('form').height());

        var data = JSON.parse('{!! addslashes(json_encode($tree)) !!}');

        var tree = new Tree($('#tree').get(0), data, {
            move: false
        });

        function spawn_controls(){

            // main buttons
            var controls = d3.select("#tree")
                .append("div")
                .attr("class", "main_controls");

            var controls_tree = controls
                .append("button")
                .text("TREE")
                .attr("class", "purple active")
                .on("click", function(){
                    controls_tree.attr("class", "active");
                    controls_cluster.attr("class", "");
                    $('#tree').html("");
                    tree = new Tree($('#tree').get(0), data, {
                        move: false
                    });
                    spawn_controls();
                    tree.draw();
                    tree.timeline();
                    tree.fit();
                });

            var controls_cluster = controls
                .append("button")
                .text("CLUSTER")
                .attr("class", "purple")
                .on("click", function(){
                    controls_tree.attr("class", "");
                    controls_cluster.attr("class", "active");
                    $('#tree').html("");
                    tree = new Tree($('#tree').get(0), data, {
                        move: false
                    });
                    spawn_controls();
                    tree.drawCluster();
                    tree.timeline();
                    tree.fit();
                });
        }
        spawn_controls();
        tree.draw();
        tree.timeline();
        tree.fit();

    </script>

@endsection
