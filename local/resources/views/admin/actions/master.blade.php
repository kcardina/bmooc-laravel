@extends('admin.master')

@section('title', 'Actions')

@section('nav_secondary')
    <li class="{{ Menu::active('thumbnails') }}">
        <a href="/admin/actions/thumbnails">thumbnails</a>
    </li>
    <li class="{{ Menu::active('tags') }}">
        <a href="/admin/actions/tags">tags</a>
    </li>
@endsection

@section('content')
    @parent
@endsection


@section('scripts')
    @parent
@endsection
