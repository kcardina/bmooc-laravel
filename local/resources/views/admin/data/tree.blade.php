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

        Tree.prototype.slider = function(){

            formatDate = d3.time.format("%d %b %Y");

            // scale function
            var timeScale = d3.time.scale()
              .domain([
                  d3.min(this.tree.nodes(this.data), function(d){
                      var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                      return date;
                  }),
                  d3.max(this.tree.nodes(this.data), function(d){
                      var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                      return date;
                  })
              ])
              .range([0, this.width])
              .clamp(true);

            // initial value (max date)
            var startingValue = d3.max(this.tree.nodes(this.data), function(d){
                  var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d.created_at);
                  return date;
              })

            // defines brush
            var brush = d3.svg.brush()
                .x(timeScale)
                .extent([startingValue, startingValue])
                .on("brush", brushed);

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
                .attr("transform", "translate(0," + (this.height - 25) + ")")
                // inroduce axis
                .call(axis)
                .select(".domain");

            // add slider handle on parentNode, we don't want to scale it
            var slider = d3.select(this.svg.node().parentNode).append("g")
              .attr("class", "slider")
              .call(brush);

            slider.selectAll(".extent,.resize")
              .remove();

            slider.select(".background")
              .attr("height", this.height);

            // HANDLE
            var handle = slider.append("g")
              .attr("class", "handle")

            handle.append("circle")
              .attr("transform", "translate(0," + (this.height - 25) + ")")
              .attr("r", 5)

            handle.append('text')
              .text(startingValue)
              .attr("transform", "translate(" + 0 + " ," + (this.height - 55) + ")");

            slider
              .call(brush.event)

            function brushed(pointer) {

                //console.log(pointer.svg);

              var value = brush.extent()[0];

              if (d3.event.sourceEvent) { // not a programmatic event
                value = timeScale.invert(d3.mouse(this)[0]);
                brush.extent([value, value]);
              }

              handle.attr("transform", "translate(" + timeScale(value) + ",0)");
              handle.select('text').text(formatDate(value));
            }
        }
    </script>

    <script>

        $('#tree').height($(document).height() - $('header').height() - $('nav').height() - $('form').height());

        var data = JSON.parse('{!! addslashes(json_encode($tree)) !!}');

        var tree = new Tree($('#tree').get(0), data);

        tree.draw();
        tree.slider();
        tree.resize();

    </script>

@endsection
