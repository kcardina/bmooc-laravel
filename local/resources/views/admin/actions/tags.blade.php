@extends('admin.actions.master')

@section('content')
    @parent

    <div class="row">
        <div class="columns">
           <em>Pre version 2.0, tags provided by the user were not checked against the database. This caused some tags to have one or more duplicates in the database. Running the script will also update the "updated_at" value in corresponding rows in the "tags" and "artefacts_tags" tables.</em>
            <h2>Found {{sizeof($duplicates)}} duplicate tags.</h2>
            <table>
                <thead>
                    <tr>
                        <td>Tag</td>
                        <td>ID's</td>
                        <td>Occurence</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($duplicates as $duplicate)
                        <tr>
                            <td>{{ $duplicate->tag }}</td>
                            <td>{{ implode(" ", $duplicate->id) }}</td>
                            <td>{{ implode(" ", $duplicate->times_used) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="columns">
           {!! Form::open(array('method' => 'post')) !!}
            <input type="submit" class="button" value="Clean"/>
           {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection
