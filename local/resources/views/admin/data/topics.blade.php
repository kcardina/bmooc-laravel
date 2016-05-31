@extends('admin.data.master')

@section('content')

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
         * EXTEND TREE
         */

        Tree.prototype.renderTags = function(){

            var nodes = JSON.parse('{!! addslashes(json_encode($list)) !!}');
            var tags = JSON.parse('{!! addslashes(json_encode($tags)) !!}');

            console.log(nodes);
            console.log(tags);

            var force = d3.layout.force()
                .theta(0)
                .gravity(0.015)
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
                        var st = {source: l[i].thread, target: l[j].thread, value: 1};

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

            console.log(links);

            // gebruik de index (gekoppeld aan de thread) in de array ipv id voor Force layout
            links.forEach(function(e) {
                var sourceNode = nodes.indexOf(nodes.filter(function(n) { return n.thread === e.source; })[0]);

                var targetNode = nodes.indexOf(nodes.filter(function(n) { return n.thread === e.target; })[0]);

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
        Tree.prototype.drawNodes = function(node){
            // Enter the nodes.
            var nodeEnter = node.enter().append("g")
                .attr("class", "node")
                .attr("transform", function(d) {
                    return "translate(" + d.y + "," + d.x + ")";
                });

            //text
            if(this.options.showImages){
                nodeEnter
                    .filter(function(d) { return !d.hidden })
                    .append("a")
                    .attr("xlink:href", function(d) {
                        return "/topic/"+d.id;
                    })
                    .append("text")
                    .attr('y', 0)
                    .text(function(d) { return splitString(d.title); });
            } else {
                nodeEnter.append("a")
                .attr("xlink:href", function(d) {
                    return "/topic/"+d.id;
                })
                .append("circle")
                .attr("cx", 2.5)
                .attr("cy", 0)
                .attr("r", 5);
            }
        }
    </script>

    <script>
        $('#tree').height($(document).height() - $('header').height() - $('nav').height() - $('form').height() - $("#uitleg").height());

        var data = JSON.parse('{!! addslashes(json_encode($topics)) !!}');

        var tree = new Tree($('#tree').get(0), data, {
            interactive: false,
            showImages: false
        });
        tree.render("tags");

        // main buttons
        var controls = d3.select("#tree")
            .append("div")
            .attr("class", "main_controls");

        var controls_tree = controls
            .append("button")
            .text("Text")
            .attr("class", "purple active")
            .on("click", function(){
                tree.options.showImages = !tree.options.showImages;
                tree.render("tags");
            });

    </script>

@endsection
