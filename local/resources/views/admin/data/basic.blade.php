@extends('admin.data.master')

@section('content')
    @parent

    <div class="row divide">
        <div class="columns">
            <h2>General information</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-4">
            <dl>
                <dt># Artefacts</dt>
                <dd>{{ $artefacts->count }}</dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt># Tags</dt>
                <dd>{{ $tags->count }}</dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt># Users</dt>
                <dd>{{ $users->count }}</dd>
            </dl>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>Artefacten</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-3">
            <dl>
                <dt>Text</dt>
                <dd>{{ $artefacts->types['text'] }} <small>({{ round(($artefacts->types['text']/$artefacts->count)*100) }}%)</small></dd>
            </dl>
        </div>
        <div class="columns medium-3">
            <dl>
                <dt>Image</dt>
                <dd>{{ $artefacts->types['image'] }} <small>({{ round(($artefacts->types['image']/$artefacts->count)*100) }}%)</small></dd>
            </dl>
        </div>
        <div class="columns medium-3">
            <dl>
                <dt>Video</dt>
                <dd>{{ $artefacts->types['video'] }} <small>({{ round(($artefacts->types['video']/$artefacts->count)*100) }}%)</small></dd>
            </dl>
        </div>
        <div class="columns medium-3">
            <dl>
                <dt>PDF</dt>
                <dd>{{ $artefacts->types['pdf'] }} <small>({{ round(($artefacts->types['pdf']/$artefacts->count)*100) }}%)</small></dd>
            </dl>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>Tags</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-4">
            <dl>
                <dt>Top 10</dt>
                <dd>
                    <ol>
                        @foreach($tags->topten as $tag)
                        <li>{{$tag->tag}} - {{$tag->times_used}} <small>({{round(($tag->times_used/$tags->count)*100)}}%)</small></li>
                        @endforeach
                    </ol>
                </dd>
            </dl>
        </div>
        <div class="columns medium-4 pullup">
            <dl>
                <dt>Occurs only once</dt>
                <dd>
                    <ol>
                        @foreach($tags->single as $tag)
                        <li>{{$tag->tag}}</li>
                        @endforeach
                    </ol>
                </dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Occurs in multiple topics</dt>
                <dd><em>In progress...</em></dd>
            </dl>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>Users</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-4">
            <dl>
                <dt>Top 10</dt>
                <dd>
                    <ol>
                        @foreach($users->topten as $user)
                        <li>{{$user->name}} - {{$user->post_count}} <small>({{round(($user->post_count/$artefacts->count)*100)}}%)</small></li>
                        @endforeach
                    </ol>
                </dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Not involved</dt>
                <dd>
                    {{ $users->passive }} <small>({{ round(($users->passive/$users->all)*100) }}%)</small>
                </dd>
            </dl>
        </div>
        <div class="columns medium-4">
            <dl>
                <dt>Distribution</dt>
                <dd id="users_distribution"></dd>
            </dl>
        </div>
    </div>

    <pre>
    </pre>

@endsection

@section('scripts')
    @parent

    <script>
        /**
         * USERS_DISTRIBUTION
         */
        $(document).ready(function(){

            var el = "#users_distribution";

            // Generate a Bates distribution of 10 random variables.
            var values = JSON.parse('{!! addslashes(json_encode($users->users)) !!}');

            // A formatter for counts.
            var formatCount = d3.format("f");

            var margin = {top: 10, right: 30, bottom: 30, left: 30},
                width = $(el).closest('.columns').width() - margin.left - margin.right,
                height = 500 - margin.top - margin.bottom;

            var x = d3.scale.linear()
                .domain([0, {{ $artefacts->count }}])
                .range([0, width]);

            // Generate a histogram using twenty uniformly-spaced bins.
            var data = d3.layout.histogram()
                .bins(x.ticks(10))
                .value(function(d) {
                    return d.post_count;
                })
                (values);

            var y = d3.scale.linear()
                .domain([0, d3.max(data, function(d) { return d.y; })])
                .range([height, 0]);

            var xAxis = d3.svg.axis()
                .scale(x)
                .orient("bottom");

            var svg = d3.select(el).append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
              .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            var bar = svg.selectAll(".bar")
                .data(data)
              .enter().append("g")
                .attr("class", "bar")
                .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

            bar.append("rect")
                .attr("x", 1)
                .attr("width", x(data[0].dx) - 1)
                .attr("height", function(d) { return height - y(d.y); });

            bar.append("text")
                .attr("dy", ".75em")
                .attr("y", 6)
                .attr("x", x(data[0].dx) / 2)
                .attr("text-anchor", "middle")
                .text(function(d) { return formatCount(d.y); });

            svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);
        });
    </script>

@endsection
