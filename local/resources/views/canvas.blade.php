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

                if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                  alert('The File APIs are not fully supported in this browser.');
                  return;
                }

                input = document.getElementById('fileinput');

                if (!input) {
                  alert("Um, couldn't find the fileinput element.");
                }
                else if (!input.files) {
                  alert("This browser doesn't seem to support the `files` property of file inputs.");
                }
                else if (!input.files[0]) {
                  alert("Please select a file before clicking 'Load'");
                }
                else {
                    file = input.files[0];

                    fr = new FileReader();
                    fr.onload = function(e){

                        if(file.type.match('image.*')){
                            var img = new Image();
                            img.onload = function(){
                                getThumbnail(img, 100);
                                getThumbnail(img, 1000);
                            }
                            img.src = event.target.result;
                        }

                        if(file.type.match('application/pdf') || file.type.match('application/x-pdf')){
                            var pdfAsArray = convertDataURIToBinary(event.target.result);
                            getPdfThumbnail(pdfAsArray, 100);
                            getPdfThumbnail(pdfAsArray, 1000);
                        }
                    }

                  fr.readAsDataURL(file);
                }
            });
        });

        function getThumbnail(img, s) {
            var c = document.createElement("canvas");
            var ctx = c.getContext("2d");

            var ratio = img.height/img.width;
            if(ratio > 1){ // portrait
                if(img.height < s) return;
                c.height = s;
                c.width = s/ratio;
            } else{ // landscape
                if(img.width < s) return;
                c.width = s;
                c.height = s*ratio;
            }

            ctx.drawImage(img, 0, 0, c.width, c.height);

            store(c);
        }

        function getPdfThumbnail(url, s){

            PDFJS.workerSrc = '/js/pdf.worker.js';
            var c = document.createElement("canvas");
            var ctx = c.getContext("2d");

            PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
                pdf.getPage(1).then(function getPageHelloWorld(page) {
                    var viewport = page.getViewport(1);

                    var ratio = viewport.height/viewport.width;
                    if(ratio > 1){ // portrait -> s = max height
                        var scale = s / viewport.height;
                        viewport = page.getViewport(scale);
                    } else{ // landscape -> s = max width
                        var scale = s / viewport.width;
                        viewport = page.getViewport(scale);
                    }

                    c.height = viewport.height;
                    c.width = viewport.width;

                    //
                    // Render PDF page into canvas context
                    //
                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };

                    var renderTask = page.render(renderContext);

                    renderTask.promise.then(function(){
                        store(c);
                    });
                });
            });
        }

        function store(c){
            $('body').append(c);
        }

        var BASE64_MARKER = ';base64,';

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

    </script>

</body>
</html>
