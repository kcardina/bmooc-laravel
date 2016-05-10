@extends('master')

@section('title', 'bMOOC')

@section('header_actions')
    @if (isset($user) && $user->role=="editor")
        <button class="big plus pullup" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic">Start a new topic</button>
    @endif
@stop

@section('content')
    <div class="row">
        <div class="columns vis"></div>
    </div>
@stop


@section('scripts')
   {!! HTML::script('js/d3.min.js') !!}
   {!! HTML::script('js/d3plus.min.js') !!}
    <script>
        var data = {};
        data.list = JSON.parse('{!! addslashes(json_encode($topics)) !!}');
        data.links = JSON.parse('{!! addslashes(json_encode($links)) !!}');;

        console.log(data);

        var vis = new Vis($('.vis').get(0), data);
        vis.render('force');
    </script>
@stop
