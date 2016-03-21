@extends('admin.data.master')

@section('content')
    @parent

    <div class="row">
        <div class="columns graph">

        </div>
    </div>

@endsection


@section('scripts')
    @parent

    <script>
        /**
         * USERS_DISTRIBUTION
         */

        var el = ".graph";

        // Set the dimensions of the canvas / graph
        var margin = {top: 30, right: 20, bottom: 30, left: 50},
            width = $(el).closest('.columns').width() - margin.left - margin.right,
            height = 270 - margin.top - margin.bottom;

        // Parse the date / time
        var parseDate = function(d){
            var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d);
            var format = d3.time.format('%Y-%m-%d');
            var stripped = format(date);
            return d3.time.format('%Y-%m-%d').parse(stripped);
        }

        // Set the ranges
        var x = d3.time.scale().range([0, width]);
        var y = d3.scale.linear().range([height, 0]);

        // Define the axes
        var xAxis = d3.svg.axis().scale(x)
            .orient("bottom").ticks(5);

        var yAxis = d3.svg.axis().scale(y)
            .orient("left").ticks(5);

        // Define the line
        var valueline = d3.svg.line()
            .x(function(d) {
                return x(d.key);
            })
            .y(function(d) {
                return y(d.values);
            });

        // Adds the svg canvas
        var svg = d3.select(el)
            .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
            .append("g")
                .attr("transform",
                      "translate(" + margin.left + "," + margin.top + ")");

        var artefacts = JSON.parse('{!! addslashes(json_encode($artefacts)) !!}');

        /* data.forEach(function(d) {
            d.date = parseDate(d.created_at);
            d.close = d.id;
            console.log(d);
        }); */


        var data = d3.nest()
          .key(function(d) {
              return parseDate(d.created_at);
          })
          .rollup(function(d) {
           return d3.sum(d, function(g) {return 1 });
          }).entries(artefacts);

        console.log(data);

        // Scale the range of the data
        x.domain(d3.extent(data, function(d) {
            return d.key;
        }));
        y.domain([0, d3.max(data, function(d) {
            return d.values;
        })]);

        // Add the valueline path.
        svg.append("path")
            .attr("class", "line")
            .attr("d", valueline(data));

        // Add the X Axis
        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        // Add the Y Axis
        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis);

    </script>
@endsection
