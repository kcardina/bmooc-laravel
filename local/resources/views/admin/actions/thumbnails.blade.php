@extends('admin.actions.master')

@section('content')
    @parent

<div class="row">
   <div class="columns">
    <table role="grid">
        <thead>
            <tr>
                <th width="100">ID</th>
                <th>URL</th>
                <th width="100">TYPE</th>
                <th width="50">ORIGINAL</th>
                <th width="50">SMALL</th>
                <th width="50">LARGE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($artefacts as $artefact)
            <tr>
                <td class="id">{{ $artefact->id }}</td>
                <td class="url">{{ $artefact->url }}</td>
                <td class="type">
                    @if ($artefact->artefact_type == 29)
                        <span class="img">img</span>
                    @elseif ($artefact->artefact_type == 33)
                        <span class="pdf">pdf</span>
                    @endif
                </td>
                <td class="original">
                    @if (in_array('original', $artefact->sizes))
                        <span class="ok">&#10004;</span>
                    @endif
                </td>
                <td class="small">
                    @if (in_array('small', $artefact->sizes))
                        <span class="ok">&#10004;</span>
                    @endif
                </td>
                <td class="large">
                    @if (in_array('large', $artefact->sizes))
                        <span class="ok">&#10004;</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
<div class="row">
    <div class="columns">
            <button id="generate" style="width: 100%">Generate</button>
    </div>
</div>
@endsection

@section('scripts')
    @parent

    {!! HTML::script('js/foundation.min.js') !!}
    {!! HTML::script('js/pdf.js') !!}
    {!! HTML::script('js/app.js') !!}
    <script>
        $(document).ready(function(){
            $('#generate').click(function(){

                // loop through
                $('.artefacts tr').each(function (i, row) {
                    var filename = $('td.url', row).html();
                    if($('td.original', row).find('.ok').length > 0){
                        //var url = host + '/uploads/' + $('td.url', row).html();
                        var url = host + '/artefact/' + $('td.id', row).html() + '/original';
                        if(!$('td.small .ok', row).length > 0){
                            var t_100 = new ThumbnailFromURL(url, 100);
                            var small = $('td.small', row);
                            small.append('<img src="{{ URL::to('/') }}/img/loader.gif" />');
                            $.when(t_100.generate()).done(function(data){
                                if(t_100.hasData) {
                                    $.ajax({
                                        type: "POST",
                                        url: host + '/admin/thumbnails',
                                        data: {
                                            size: 'small',
                                            filename: filename,
                                            file: t_100.get(),
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        success: function(data){
                                            console.log(data);
                                        },
                                        error: function(data){
                                            console.log(data.responseJSON.message);
                                        }
                                    });
                                    small.html('&#10004;');
                                } else {
                                    small.html('');
                                }
                            }).fail(function(data){
                                console.log(data);
                            });
                        }
                        if(!$('td.large .ok', row).length > 0){
                            var t_1000 = new ThumbnailFromURL(url, 1000);
                            var large = $('td.large', row);
                            large.append('<img src="{{ URL::to('/') }}/img/loader.gif" />');
                            $.when(t_1000.generate()).done(function(data){
                                if(t_1000.hasData) {
                                    $.ajax({
                                        type: "POST",
                                        url: host + '/admin/thumbnails',
                                        data: {
                                            size: 'large',
                                            filename: filename,
                                            file: t_1000.get(),
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        success: function(data){
                                            console.log(data);
                                        },
                                        error: function(data){
                                            console.log(data.responseJSON.message);
                                        }
                                    });
                                    large.html('&#10004;');
                                } else {
                                    large.html('');
                                }
                            }).fail(function(data){
                                console.log(data);
                            });
                        }
                    }
                });
            });
        });

        function ThumbnailFromURL(url, size, type){
            Thumbnail.call(this, null, size);
            this.url = url;
        }

        ThumbnailFromURL.prototype = Object.create(Thumbnail.prototype);

        ThumbnailFromURL.prototype.generate = function(){
            if (window.File || !window.FileReader || !window.FileList || !window.Blob){
                this.readFile();
            } else {
                this.dfd.reject('Your browser does not support the FileReader class');
            }
            return this.dfd.promise();
        }

        ThumbnailFromURL.prototype.readFile = function(){
            var xhr = new XMLHttpRequest();
            var pointer = this;
            xhr.responseType = 'blob';

            xhr.onload = function() {
                pointer.file = xhr.response;
                Thumbnail.prototype.readFile.call(pointer);
            };

            xhr.onerror = function(e) {
                pointer.dfd.reject('Failed to get file from given url');
            };

            xhr.open('GET', this.url);
            xhr.send();
        }


    </script>

@endsection
