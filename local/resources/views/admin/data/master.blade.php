@extends('admin.master')

@section('title', 'Data')

@section('nav_secondary')
    <li class="{{ Menu::active('admin') }}">
        <a href="/admin/data/basic">basic</a>
    </li>
    <li class="{{ Menu::active('progress') }}">
        <a href="/admin/data/progress">progress</a>
    </li>
    <li class="{{ Menu::active('tree') }}">
        <a href="/admin/data/tree">tree</a>
    </li>
    <li class="{{ Menu::active('analytics') }}">
        <a href="/admin/data/analytics">analytics</a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="columns">
           <form id="topics">
                <select name="topic">
                    <option value="all">ALL TOPICS</option>
                    <option disabled>──────────</option>
                   @foreach($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    {!! HTML::script('//d3js.org/d3.v3.min.js') !!}
    {!! HTML::script('js/d3plus.min.js') !!}

    <script>
        $(function(){
            $("#topics").on('change', function(){
                $("#topics").submit();
            });
        });
    </script>

@endsection
