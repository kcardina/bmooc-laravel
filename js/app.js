/************
* VALIDATIE *
************/

/**
 * Main validation (abide)
 */
$(document).foundation({
    abide : {
        timeout: 1000,
        validators: {
            tag_new: function(el, required, parent){
                var tags = [];
                var valid = true;
                $('[data-abide-validator="tag_new"]').each(function(){
                    if($.inArray($(this).val(), tags) > -1){
                        valid = false;
                    }
                    tags.push($(this).val());
                });
                return valid;
            },
            tag_existing: function(el, required, parent){
                var tags = [];
                var valid = true;
                $('#answer_tags input[type=checkbox]:checked').each(function() {
                    if ($.inArray($(this).next().text(), tags) > -1) {
                        valid = false;
                    }
                    tags.push($(this).next().text());
                })
                $('[data-abide-validator="tag_existing"]').each(function(){
                    if($.inArray($(this).val(), tags) > -1){
                        valid = false;
                    }
                    tags.push($(this).val());
                });
                if(el.value == "" || el.value == null) valid = false;
                return valid;
            },
            tag_select: function(el, required, parent){
                var valid = true;
                if (parent.parent().find('input').length){
                    if (parent.parent().find('input:checked').length != 2) {
                        valid = false;
                        parent.parent().find('small.error').css('display', 'block');
                    } else {
                        parent.parent().find('small.error').css('display', 'none');
                    }
                }
                return valid;
            },
            filesize: function(el, required, parent){
                var valid = true;
                if(el.files.length > 0){
                    var f = el.files[0];
                    if (f.size > 5120000) {
                        valid = false;
                    }
                }
                return valid;
            },
            filetype: function(el, required, parent){
                var div = parent;
                var valid = true;
                var msg;

                // check if anything is selected
                if(!div.find('.type_select').hasClass('active')){
                    valid = false;
                    msg = "Please choose on of the file types."
                }

                // text
                if(div.find('button#type_text').hasClass('active')){
                    if(div.find('.ql-editor').text().length <= 0){
                        valid = false;
                        msg = "Please enter some text.";
                    } else {
                        div.find('textarea').val(div.find('.ql-editor').html());
                    }
                // image
                } else if(div.find('button#type_image').hasClass('active')){
                    if(div.find('.input_file input[type=file]').val().length == 0){
                        valid = false;
                        msg = "Please select an image to upload."
                    }
                // video
                } else if(div.find('button#type_video').hasClass('active')){
                    if(div.find('.input_url input[type=text]').val().length == 0){
                        valid = false;
                        msg = "Please enter a link to a video on YouTube or Vimeo."
                    }
                // file
                } else if(div.find('button#type_file').hasClass('active')){
                    if(div.find('.input_file input[type=file]').val().length == 0){
                        valid = false;
                        msg = "Please select a PDF to upload."
                    }
                }

                if(!valid){
                    div.find('.error.filetype_error').html(msg);
                    div.find('.error.filetype_error').css('display', 'block');
                } else{
                    div.find('.error.filetype_error').css('display', 'none');
                }
                return valid;
            }
        }
    }
});

/**
 * Toon of verberg invoervelden van antwoordtypes
 * @param {event} e Event van de knop die werd geklikt
 */
function showAnswerType(e) {
    e.preventDefault();
    var parent = $(this).parents(".filetype");
    var $this = $(this);

    // clear form and errors
    parent.find('.filetype_error').hide();

    if($this.hasClass('active')){
        return false;
    }

    parent.find('.type_select').removeClass('active');
    $this.addClass('active');
    parent.find('.type_input').hide();
    if ($this.attr('id') == 'type_text') {
        parent.find('.input_textarea').slideDown();
        parent.find('.temp_type').val('text');
    } else if ($this.attr('id') == 'type_image') {
        parent.find('.filetype_label').html('Select an image to upload <small>(JPG, PNG or GIF, &lt;5MB)</small>');
        parent.find('.input_file').slideDown();
        parent.find('.temp_type').val('image');
    } else if ($this.attr('id') == 'type_video') {
        parent.find('.input_url').slideDown();
        parent.find('.temp_type').val('video');
    } else if ($this.attr('id') == 'type_file') {
        parent.find('.filetype_label').html('Select a PDF to upload. <small>(&lt;5MB. If the file is too large you can use <a href="http://smallpdf.com/compress-pdf">this free tool</a> to resize your PDF)</small>');
        parent.find('.input_file').slideDown();
        parent.find('.temp_type').val('file');
    }
}

/********
* FORMS *
********/

/* FEEDBACK */
$(function(){
    $('#feedback').submit(function(e){
        e.preventDefault();

        var name = $('#feedback #fb_name').val();
        var email = $('#feedback #fb_mail').val();
        var message = $('#feedback #fb_msg').val();
        var token = $('#feedback input[name="_token"]').val();

        $.ajax({
        type: "POST",
        url: host+'/feedback',
        data: {name:name, email:email, body:message, _token: token},
        success: function( msg ) {
            $('#feedback .mailstatus').addClass('success');
            $('#feedback .mailstatus').html(msg);
            $('#feedback .mailstatus').css('display', 'block');
            setTimeout(function() {
                $('#feedback .mailstatus').slideUp()
            }, 5000);
        },
        fail: function(msg){
            $('#feedback .mailstatus').html(msg);
            $('#feedback .mailstatus').css('display', 'block');
            setTimeout(function() {
                $('#feedback .mailstatus').slideUp()
            }, 5000);
        }
    });
    })
});

/* NEW TOPIC */
$(function(){
    $('*[data-abide="ajax"]').on('valid.fndtn.abide', function(e) {
        var form = $(this);
        e.preventDefault();

        // reset & show loading screen
        $('#progress .loader').show();
        $('#progress .message').html('Preparing your contribution for submission...');
        $('#progress').foundation('reveal', 'open');

        var input = document.querySelectorAll('#'+form.attr('id')+' .input_file input')[0];
        var parent = form.parents('[data-reveal]');

        var t_100 = new Thumbnail(input, 100);
        var t_1000 = new Thumbnail(input, 1000);

        $.when(t_100.generate(), t_1000.generate()).done(function(){
            if(t_100.hasData) {
                console.log(t_100.get());
                $('<input>', {
                    type: 'hidden',
                    id: 'thumbnail_small',
                    name: 'thumbnail_small',
                    value: t_100.get()
                }).appendTo(form);
            }
            if(t_1000.hasData) {
                $('<input>', {
                    type: 'hidden',
                    id: 'thumbnail_large',
                    name: 'thumbnail_large',
                    value: t_1000.get()
                }).appendTo(form);
            }
            formSubmit(form, parent);
        }).fail(function(data) {
            formSubmit(form, parent);
        });
    });
});

function formSubmit(form, parent){
    $('#progress .message').html('Uploading files...');
     var options = {
        success: function(data){
            if(data.refresh) location.reload(true);
            else window.location = data.url;
        },
        error: function(data){
            $('#progress .loader').hide();
            $('#progress .message').html('<h2>Oops!</h2><p>Something went wrong while  saving your contribution.</p>');
            $('#progress .message').append('<small class="error">' + data.responseJSON.message + '</small>');
            $('#progress .message').append('<a href="#" data-reveal-id="' + parent.attr('id') + '" class="emphasis">Please try again</a>');
        }
     };

    // bind form using 'ajaxForm'
    form.ajaxSubmit(options);
}

/*********
* RENDER *
*********/

/**
 * Show an artefact in the desired container
 * The container should have two divs, .loader & .artefact
 * @param div A div which contains two childeren, .loader & .artefact
 * @param type The type of the artefact
 * @param data The artefact
 */
function render(div, type, data){
    var html;
    var loadImg = false;

    div.find('.artefact').hide();
    div.find('.loader').show();

    switch (type) {
        case 'text':
            html = "<div class=\"textContainer\"><div class=\"text\"><h2>" + data.title + "</h2>" + data.contents + "</div></div>";
            break;
        case 'local_image':
            html = '&nbsp;<img src="' + host + "/uploads/" + data.url + '">';
            loadImg = true;
            break;
        case 'remote_image':
            html = '&nbsp;<img src="' + data.url + '">';
            loadImg = true;
            break;
        case 'video_youtube':
            html = '<iframe  src="' + data.url + '?autoplay=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 'video_vimeo':
            html = '<iframe src="' + data.url + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 'remote_document':
            html = 'Please, <a href="' + data.url + '" target="_new">download</a> the document to open...';
            break;
        case 'local_document':
            html = 'Please, <a href="' + host + "/uploads/" + data.url + '" target="_new">download</a> the document to open...';
            break;
        case 'local_pdf':
            html = '<object data="' + host + "/uploads/" + data.url + '" type="application/pdf"><a href="' + host + "/uploads/" + data.url + '">[PDF]</a></object>';
            break;
        case 'remote_pdf':
            html = '<object data="' + data.url + '" type="application/pdf"><a href="' + data.url + '">[PDF]</a></object>';
            break;
        default:
            html = '<p>Oops. Something went wrong. Try reloading the page.</p>';
            break;
    }

    div.find('.artefact').html(html);

    if (loadImg) {
        div.imagesLoaded(function () {
            div.stop(true, true)
            div.find('.loader').hide();
            div.find('.artefact').fadeIn();
        });
    } else {
        div.stop(true, true)
        div.find('.loader').hide();
        div.find('.artefact').fadeIn();
    }
}

/** Generate thumbnails using HTML5 Canvas & PDFJS. */
var Thumbnail = (function(){

    var BASE64_MARKER = ';base64,';

    /**
     * Create a thumbnail.
     * @param {dom element} el - The input element containing a image or pdf.
     * @param {number} size - The size of the thumbnails bounding box.
     */
    function Thumbnail(el, size){
        this.dfd = new $.Deferred();
        this.c = document.createElement("canvas");
        this.ctx = this.c.getContext("2d");
        this.el = el;
        this.size = size;
        this.hasData = false;
        this.file = null;
    }

    /**
     * Generate the thumbnail.
     * @return {promise} A Jquery promise.
     */
    Thumbnail.prototype.generate = function(){
        var support = browserSupport.call(this);
        if(support.isSupported){
            this.file = this.el.files[0];
            this.readFile();
        } else {
            this.dfd.reject(support.msg);
        }
        return this.dfd.promise();
    }

    /**
     * Get the thumbnail image.
     * @return {image} A base64 encoded png.
     */
    Thumbnail.prototype.get = function(){
        return this.c.toDataURL('image/png');
    }

    /**
     * Read the input file and call the appropriate render method
     */
    Thumbnail.prototype.readFile = function(){
        var fr = new FileReader();
        var pointer = this;
        fr.onload = function(e){
            if(pointer.file.type.match('image.*')){
                var img = new Image();
                img.onload = function(){
                    pointer.render(img);
                }
                img.src = event.target.result;
            } else if(pointer.file.type.match('application/pdf') || pointer.file.type.match('application/x-pdf')){
                var pdfAsArray = convertDataURIToBinary(event.target.result);
                pointer.renderPDF(pdfAsArray);
            } else {
                pointer.dfd.reject("The uploaded file was not an image, nor a pdf");
            }
        }

        fr.onerror = function(e){
            pointer.dfd.reject("Failed to read the file");
        }

        fr.readAsDataURL(pointer.file);
    }

    /**
     * Render a thumbnail given an image.
     * @param {Image} img - The original image
     */
    Thumbnail.prototype.render = function(img){
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

    /**
     * Render a thumbnail given a pdf.
     * @param {Uint8Array} url - The original pdf, encoded as a Uint8Array
     */
    Thumbnail.prototype.renderPDF = function(url){
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

    /**
     * Check if the browser supports the FileReader class and if input file is valid
     * @return {boolean} isSupported.
     * @return {string} msg.
     */
    function browserSupport(){
        var support = {
            isSupported: true,
            msg: 'Browser supports thumbnail generation.'
        }

        if (!window.File || !window.FileReader || !window.FileList || !window.Blob){
            support = {
                isSupported: false,
                msg: 'The File APIs are not fully supported in this browser.'
            }
        } else if (!this.el) {
            support = {
                isSupported: false,
                msg: 'Couldn\'t find the fileinput element.'
            }
        } else if (!this.el.files) {
            support = {
                isSupported: false,
                msg: 'This browser doesn\'t seem to support the \'files\' property of file inputs.'
            }
        } else if (!this.el.files[0]) {
            support = {
                isSupported: false,
                msg: 'No file selected.'
            }
        }
        return support;
    }

    /**
     * Convert pdf from base64 to Uint8Array.
     * @param {base64} dataURI - A base64 encoded pdf
     * @return {Uint8Array} array - A Uint8Array encoded pdf
     */
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

/*******************
* HELPER FUNCTIONS *
*******************/

function parseDate(d) {
    var date = d.substring(0, d.indexOf(" "));
    var time = d.substring(d.indexOf(" ") + 1);
    var year = date.substring(0, 4);
    var month = date.substring(5, 7);
    var day = date.substring(8, 10);

    return(day + "/" + month + "/" + year + " " + time.substring(0, time.length - 3));
}
