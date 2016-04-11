@extends('admin.data.master')

@section('content')
    @parent

    <div class="row">
        In progress...
    </div>

   @endsection


@section('scripts')
    @parent

    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/app.js?v=' . Version::get()) !!}

    <script>

    </script>
@endsection
