<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Canvas Test</title>
</head>
<body>
   <form id="upload">
       <input type="file" id="fileinput"/>
       <input type="submit" />
    </form>

    {!! HTML::script('js/vendor/jquery.js') !!}
    {!! HTML::script('js/pdf.js') !!}
    <script>
        $(document).ready(function(){
            $('#upload').submit(function(e){
                e.preventDefault();

                input = document.getElementById('fileinput');

                var t_100 = new Thumbnail(input, 100);
                var t_1000 = new Thumbnail(input, 1000);

                $.when(t_100.generate(), t_1000.generate()).done(function(){
                    console.log('Succes! Submitting form.');
                    if(t_100.hasData) $('body').append('<img src="'+t_100.get()+'" />');
                    if(t_1000.hasData) $('body').append('<img src="'+t_1000.get()+'" />');
                }).fail(function(data) {
                    console.log( 'One or more requests failed: ' + data);
                });
            });
        });

        var Thumbnail = (function(){

            var BASE64_MARKER = ';base64,';

            // Constructor
            function Thumbnail(el, size){
                this.dfd = new $.Deferred();
                this.c = document.createElement("canvas");
                this.ctx = this.c.getContext("2d");
                this.el = el;
                this.size = size;
                this.hasData = false;
            }

            Thumbnail.prototype.generate = function(){
                var support = browserSupport.call(this);
                if(support.isSupported){
                    readFile.call(this);
                } else {
                    this.dfd.reject(support.msg);
                }
                return this.dfd.promise();
            }


            Thumbnail.prototype.get = function(){
                return this.c.toDataURL('image/png');
            }

            function readFile(){
                var file = this.el.files[0];
                var fr = new FileReader();
                var pointer = this;
                fr.onload = function(e){
                    if(file.type.match('image.*')){
                        var img = new Image();
                        img.onload = function(){
                            render.call(pointer, img);
                        }
                        img.src = event.target.result;
                    } else if(file.type.match('application/pdf') || file.type.match('application/x-pdf')){
                        var pdfAsArray = convertDataURIToBinary(event.target.result);
                        renderPDF.call(pointer, pdfAsArray);
                    } else {
                        this.dfd.reject("The uploaded file was not an image, nor a pdf");
                    }
                }

                fr.readAsDataURL(file);
            }

            function render(img){
                var ratio = img.height/img.width;
                if(ratio > 1){ // portrait
                    if(img.height < this.size){ this.dfd.resolve(); return;}
                    this.c.height = this.size;
                    this.c.width = this.size/ratio;
                } else{ // landscape
                    if(img.width < this.size){ this.dfd.resolve(); return;}
                    this.c.width = this.size;
                    this.c.height = this.size*ratio;
                }

                this.ctx.drawImage(img, 0, 0, this.c.width, this.c.height);
                this.hasData = true;
                this.dfd.resolve();
            }

            function renderPDF(url){
                var pointer = this;
                PDFJS.workerSrc = '/js/pdf.worker.js';

                PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
                    pdf.getPage(1).then(function getPageHelloWorld(page) {
                        var viewport = page.getViewport(1);
                        var ratio = viewport.height/viewport.width;
                        if(ratio > 1){ // portrait -> s = max height
                            var scale = pointer.size / viewport.height;
                            viewport = page.getViewport(scale);
                        } else{ // landscape -> s = max width
                            var scale = pointer.size / viewport.width;
                            viewport = page.getViewport(scale);
                        }

                        pointer.c.height = viewport.height;
                        pointer.c.width = viewport.width;

                        // Render PDF page into canvas context
                        var renderContext = {
                            canvasContext: pointer.ctx,
                            viewport: viewport
                        };

                        var renderTask = page.render(renderContext);

                        renderTask.promise.then(function(){
                            pointer.hasData = true;
                            pointer.dfd.resolve();
                        });
                    });
                });
            }

            function browserSupport(){
                var support = {
                    isSupported: true,
                    msg: 'Browser supports thumbnail generation.'
                }

                if (!window.File || !window.FileReader || !window.FileList || !window.Blob){
                        support = {
                            isSuported: false,
                            msg: 'The File APIs are not fully supported in this browser.'
                        }
                    }
                    if (!this.el) {
                        support = {
                            isSuported: false,
                            msg: 'Couldn\'t find the fileinput element.'
                        }
                    }
                    if (!this.el.files) {
                        support = {
                            isSuported: false,
                            msg: 'This browser doesn\'t seem to support the \'files\' property of file inputs.'
                        }
                    }
                    if (!this.el.files[0]) {
                        support = {
                            isSuported: false,
                            msg: 'No file selected.'
                        }
                    }
                return support;
            }

            function convertDataURIToBinary(dataURI) {
                var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
                var base64 = dataURI.substring(base64Index);
                var raw = window.atob(base64);
                var rawLength = raw.length;
                var array = new Uint8Array(new ArrayBuffer(rawLength));

                for(var i = 0; i < rawLength; i++) {
                    array[i] = raw.charCodeAt(i);
                }
                return array;
            }

            return Thumbnail;

        })();

    </script>

</body>
</html>
