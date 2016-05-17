@extends('master')

@section('title', 'bMOOC')

@section('header_actions')
    @if (isset($user) && $user->role=="editor")
        <button class="primary plus" data-help="index" data-help-id="new_topic" data-reveal-id="new_topic">Start a new topic</button>
    @endif
@stop

@section('header_search')
    @include('forms.search')
@stop

@section('content')
    <div class="row full">
        <div class="columns vis-container"></div>
        <div class="columns vis-fallback">
            <ul class="block text-center">
                @foreach($topics as $topic)
                    <li>
                        <a href="topic/{{ $topic->id }}">
                            <h2>{{ $topic->title }}</h2>
                            <p>{{ $topic->author }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
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

        $(document).ready(function(){
            if($('html').hasClass('svg')){
                var vis = new Vis($('.vis-container').get(0), data, {
                    interactive: false,
                    mode: 'text',
                    fit: true
                });
                vis.render('force');
                //vis.fit();
            }
        });
    </script>
@stop
