@extends('admin.master')

@section('title', 'Data')

@section('nav_secondary')
    <li class="{{ Menu::active('admin') }}">
        <a href="/admin">basic</a>
    </li>
    <li class="{{ Menu::active('progress') }}">
        <a href="/admin/data/progress">progress</a>
    </li>
    <li class="{{ Menu::active('tree') }}">
        <a href="/admin/data/tree">tree</a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="columns">
            <select>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="fiat">Fiat</option>
                <option value="audi">Audi</option>
            </select>
        </div>
    </div>
@endsection
