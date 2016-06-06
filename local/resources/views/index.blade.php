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
    <div class="row full vis-container">
        <div class="vis-gui render">

        </div>
    </div>
    <div class="row full vis-fallback">
        <div class="columns">
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

        var vis;

        $(document).ready(function(){
            $('.vis_list').on('click',function(){
                $('.vis-container').hide();
                $('.vis-fallback').show();
                $('button.tertiary').removeClass('active');
                $(this).addClass('active')
            });
            $('.vis_tree').addClass('inactive');
            $('.vis_network').on('click',function(){
                $('.vis-fallback').hide();
                $('.vis-container').show();
                $('button.tertiary').removeClass('active');
                $(this).addClass('active')
            });


            if($('html').hasClass('svg')){
                $('.vis_network').addClass('active');
                vis = new Vis($('.vis-container').get(0), data, {
                    interactive: true,
                    mode: 'text',
                    fit: true,
                    collide: false,
                    resize: true
                });
                vis.render('network');
            }
        });
    </script>
@stop
