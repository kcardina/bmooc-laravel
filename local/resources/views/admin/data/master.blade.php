@extends('admin.master')

@section('title', 'Data')

@section('nav_secondary')
    <li class="{{ Menu::active('basic') }}">
        <a href="/admin/data/basic">basic</a>
    </li>
    <li class="{{ Menu::active('progress') }}">
        <a href="/admin/data/progress">progress</a>
    </li>
    <li class="{{ Menu::active('tree') }}">
        <a href="/admin/data/tree">tree</a>
    </li>
    <li class="{{ Menu::active('topics') }}">
        <a href="/admin/data/topics">topics</a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="columns">
           <form id="topics">
                <select name="topic">
                    <option value="all">ALL TOPICS</option>
                    <option disabled>──────────</option>
                   @foreach($topics as $t)
                       @if ($t->thread == $topic)
                            <option selected value="{{ $t->thread }}">{{ $t->title }}</option>
                       @else
                           <option value="{{ $t->thread }}">{{ $t->title }}</option>
                       @endif
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    {!! HTML::script('js/d3.js') !!}
    {!! HTML::script('js/d3plus.min.js') !!}

    <script>
        $(function(){
            $("#topics").on('change', function(){
                $("#topics").submit();
            });
        });
    </script>

@endsection
