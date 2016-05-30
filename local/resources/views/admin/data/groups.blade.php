@extends('admin.data.master')

@section('content')
<div class="row">
    <div class="columns">
        <table>
            <thead>
                <tr>
                    <td>GROUP</td>
                    <td>USERS</td>
                    <td>active</td>
                    <td>passive</td>
                    <td>POSTS</td>
                    <td>TOPICS</td>
                    <td>group</td>
                    <td>other</td>
                    <td>TYPES</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection


@section('scripts')
    @parent

@endsection
