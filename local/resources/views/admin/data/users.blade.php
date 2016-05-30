@extends('admin.data.master')

@section('content')
    <div class="row">
        <div class="columns">
           <form id="topics">
                <select name="user">
                   @foreach($users as $u)
                       @if ($u->id == $user->id)
                            <option selected value="{{ $u->id }}">{{ $u->name }}</option>
                       @else
                           <option value="{{ $u->id }}">{{ $u->name }}</option>
                       @endif
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>General information</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-4">
            <dl>
                <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Email</dt>
                <dd>{{ $user->email }}</dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Last contribution</dt>
                <dd>{{ $user->last_contribution->created_at or 'Never' }}</dd>
            </dl>
        </div>
    </div>

    @if($user->contributions > 0)
    <div class="row divide">
        <div class="columns">
            <h2>Contributions</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-4">
            <dl>
                <dt>Total</dt>
                <dd>
                    <strong>{{ $user->contributions }}</strong> contributions in <strong>{{ count($user->topics) }}</strong> topics
                </dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Topics</dt>
                <dd>
                   <ul>
                    @foreach($user->topics as $topic)
                       <li>{{ $topic->title }} <strong>({{ count($topic->artefacts)}})</strong></li>
                    @endforeach
                    </ul>
                </dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Types</dt>
                <dd>
                   <ul>
                    @foreach($user->types as $type)
                       <li>{{ $type->description }} <strong>({{$type->count}})</strong></li>
                    @endforeach
                    </ul>
                </dd>
            </dl>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>Graph</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns" id="vis"></div>
    </div>
    @endif

@endsection


@section('scripts')
    @parent

    @if($user->contributions > 0)
    <script>
        /**
         * ARTEFACTS / DATE
         */

        var el = "#vis";

        // Set the dimensions of the canvas / graph
        var margin = {top: 30, right: 20, bottom: 30, left: 50},
            width = $(el).closest('.columns').width() - margin.left - margin.right,
            height = 270 - margin.top - margin.bottom;

        // Parse the date / time
        var trimDate = function(d){
            var date = d3.time.format("%Y-%m-%d %H:%M:%S").parse(d);
            return d3.time.format('%Y-%m-%d')(date);
        }
        var parseDate = function(d){
            return d3.time.format('%Y-%m-%d').parse(d);
        }

        var colors = d3.scale.category10();

        // Set the ranges
        var x = d3.time.scale().range([0, width]);
        var y = d3.scale.linear().range([height, 0]);

        // Define the axes
        var xAxis = d3.svg.axis().scale(x)
            .orient("bottom").ticks(width/100);

        var yAxis = d3.svg.axis().scale(y)
            .orient("left").ticks(5);

        // Define the line
        var valueline = d3.svg.line()
            .x(function(d) { return x(parseDate(d.thedate)); })
            .y(function(d) { return y(d.count); });

        // Adds the svg canvas
        var svg = d3.select(el)
            .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
            .append("g")
                .attr("transform",
                      "translate(" + margin.left + "," + margin.top + ")");

        var artefacts = JSON.parse('{!! addslashes(json_encode($user->artefacts)) !!}');

        console.log(artefacts);


        // Scale the range of the data
        //x.domain(d3.extent(data, function(d) { return parseDate(d.key); }));
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        var day = now.getDate();
        x.domain([parseDate('2016-02-01'), parseDate(year+'-'+month+'-'+day)]);
        y.domain([0, d3.max(artefacts, function(d) { return d.count; })]);

        var dataNest = d3.nest()
            .key(function(d) { return d.thread;})
            .entries(artefacts)

        // Add the valueline path.
        var i = 0;
        dataNest.forEach(function(d){
            svg.append("path")
                .attr("d", valueline(d.values))
                .attr("stroke", colors(d.key))
                .attr("id", "thread_" + d.key);

            svg.append("text")
                .text(function(a){
                    var topics = JSON.parse('{!! addslashes(json_encode($user->topics)) !!}');
                    console.log(topics);
                    title = topics.find(function(e){
                        return e.thread == d.key
                    });
                    return title.title;
                })
                .attr("x", 10)
                .attr("y", i*20+10 )
                .attr("fill", colors(d.key))
                .attr("class", "legend");
            i++
        });


        /*var data = d3.nest()
          .key(function(d) { return trimDate(d.created_at); })
          .rollup(function(d) { return d3.sum(d, function(g) {return 1 }); })
          .entries(artefacts);*/

        // Add the scatterplot
        svg.selectAll("dot")
            .data(artefacts)
            .enter().append("circle")
            .attr("r", 3.5)
            .attr("cx", function(d) {return x(parseDate(d.thedate)); })
            .attr("cy", function(d) { return y(d.count); })
            .attr("id", function(d) {return 'thread_' + d.thread})
            .attr("fill", function(d) {return colors(d.thread)})
            .append("svg:title")
            .text(function(d){ return d.thedate + " - " + d.count + " additions" });

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
    @endif


@endsection
