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
                <dt>Single occurence</dt>
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
                <dt>Occurence in multiple topics</dt>
                <dd>123 (3%)</dd>
            </dl>
        </div>
    </div>

    <div class="row divide">
        <div class="columns">
            <h2>Users</h2>
        </div>
    </div>
    <div class="row">
        <div class="columns medium-6">
            <dl>
                <dt>Top 10</dt>
                <dd>
                    <ol>
                        <li>test - 55 posts (35%)</li>
                    </ol>
                </dd>
            </dl>
        </div>
        <div class="columns medium-6">
            <dl>
                <dt>Verdeling gebruikers</dt>
                <dd>grafiek</dd>
            </dl>
        </div>
    </div>
@endsection
