<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bMOOC</title>

    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/admin.css') !!}
    {!! HTML::script('js/vendor/modernizr.js') !!}
</head>
<body>

   <header>
       <h1>bMOOC</h1>
   </header>

   <nav>
       <ul>
           <li><a href="home">Home</a></li>
           <li><a href="stats">Stats</a></li>
           <li><a href="actions">Actions</a></li>
       </ul>
   </nav>

   <h1>Stats</h1>
        <h2>Algemeen</h2>
           <dl>
               <dt>Aantal artefacten</dt>
               <dd>{{ $artefacts->aantal }}</dd>
               <dt>Aantal gebruikers</dt>
               <dd>{{ $users->aantal }}</dd>
               <dt>Aantal tags</dt>
               <dd>{{ $tags->aantal }}</dd>
           </dl>

        <h2>Artefacten</h2>
        <h3>Aantal per type</h3>
            <dl>
                @foreach ($artefacts->types as $type => $value)
                    <dt>{{ $type }}</dt>
                    <dd>{{ $value }}</dd>
                @endforeach
            </dl>
        <h3>Evolutie</h3>
            <div id="artefacts_evolution"></div>

        <h2>Users</h2>
        <h3>Top 10</h3>
            <ol>
                @foreach ($users->topten as $topuser)
                        <li>{{ $topuser->name }} - {{ $topuser->theCount }} posts ({{ round(($topuser->theCount/$artefacts->aantal)*100) }}%)</li>
                @endforeach
            </ol>
        <h3>Top 10</h3>
            <dl>
                <dt>Aantal passieve gebruikers (0 posts)</dt>
                <dd>{{ $users->passive }} ({{ round(($users->passive/$users->aantal)*100) }}%)</dd>
            </dl>


        <h2>Tags</h2>
        <h3>Top 10</h3>
            <ol>
                @foreach ($tags->topten as $tag)
                        <li>{{ $tag->tag }} - {{ $tag->times_used }} ({{ round(($tag->times_used/$artefacts->aantal)*100) }}%)</li>
                @endforeach
            </ol>

    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('//d3js.org/d3.v3.min.js') !!}
    <script>
        $(document).foundation();
    </script>
    <script>
        var margin = {top: 20, right: 20, bottom: 30, left: 50},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var formatDate = d3.time.format("%d-%b-%y");

        var x = d3.time.scale()
            .range([0, width]);

        var y = d3.scale.linear()
            .range([height, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient('bottom')
            .ticks(d3.time.days, 1)
            .tickFormat(d3.time.format('%a %d'))
            .tickSize(0)
            .tickPadding(8);

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        var line = d3.svg.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) { return y(d.close); });

        var svg = d3.select("#artefacts_evolution").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var data = {!! $artefacts->progress !!};
            //http://jsfiddle.net/robdodson/KWRxW/
          //x.domain(d3.extent(data, function(d) { return d.date; }));
          x.domain([new Date(data[0].date), d3.time.day.offset(new Date(data[data.length - 1].date), 1)])
          y.domain(d3.extent(data, function(d) { return d.amount; }));

          svg.append("g")
              .attr("class", "x axis")
              .attr("transform", "translate(0," + height + ")")
              .call(xAxis);

          svg.append("g")
              .attr("class", "y axis")
              .call(yAxis)
            .append("text")
              .attr("transform", "rotate(-90)")
              .attr("y", 6)
              .attr("dy", ".71em")
              .style("text-anchor", "end")
              .text("Price ($)");

          svg.append("path")
              .data(data)
              .attr("class", "line")
              .attr("d", line);

    </script>
</body>
</html>
