/**
 * Main validation (abide)
 */
$(document).foundation({
    abide : {
        validators: {
            tag_new: function(el, required, parent){
                var tags = [];
                var valid = true;
                $('[data-abide-validator="tag"]').each(function(){
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
            filesize: function(el, required, parent){
                var valid = true;
                if(el.files.length > 0){
                    var f = el.files[0];
                    if (f.size > 2000000) {
                        valid = false;
                    }
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
        parent.find('.filetype_label').html('Select an image to upload <small>(JPG, PNG or GIF, &lt;2MB)</small>');
        parent.find('.input_file').slideDown();
        parent.find('.temp_type').val('image');
    } else if ($this.attr('id') == 'type_video') {
        parent.find('.input_url').slideDown();
        parent.find('.temp_type').val('video');
    } else if ($this.attr('id') == 'type_file') {
        parent.find('.filetype_label').html('Select a PDF to upload. <small>(&lt;2MB. If the file is too large you can use <a href="http://smallpdf.com/compress-pdf">this free tool</a> to resize your PDF)</small>');
        parent.find('.input_file').slideDown();
        parent.find('.temp_type').val('file');
    }
}

/**
 * validatiefunctie voor antwoordtypes
 * Gebruik {!! Form::open(array('data-abide', 'onsubmit'=>'return validate("newTopicForm")')) !!}
 * @param {string} id Id van het formulier dat gevalideerd wordt.
 * @return {boolean} valid
 */
function validate(id){
    var div = $("#" + id);
    var valid = true;
    var msg;

    // check if anything is selected
    if(!div.find('.type_select').hasClass('active')){
        valid = false;
        msg = "Please choose on of the file types."
    }

    // text
    if(div.find('button#type_text').hasClass('active')){
        if(div.find('.filetype .ql-editor').text().length <= 0){
            valid = false;
            msg = "Please enter some text.";
        } else {
            div.find('.filetype textarea').val(div.find('.filetype .ql-editor').html());
        }
    // image
    } else if(div.find('button#type_image').hasClass('active')){
        if(div.find('.filetype input[type=file]').val().length == 0 && div.find('.filetype input[type=text]').val().length == 0){
            valid = false;
            msg = "Please enter a link or upload an image."
        }
        if($('.filetype input[type=file]').val().length != 0 && div.find('.filetype input[type=text]').val().length != 0){
            valid = false;
            msg = "Only one of the options can be chosen."
        }
    // video
    } else if(div.find('button#type_video').hasClass('active')){
        if(div.find('.filetype input[type=text]').val().length == 0){
            valid = false;
            msg = "Please enter a link to a video on YouTube or Vimeo."
        }
    // file
    } else if(div.find('button#type_file').hasClass('active')){
        if(div.find('.filetype input[type=file]').val().length == 0 && div.find('.filetype input[type=text]').val().length == 0){
            valid = false;
            msg = "Please enter a link or upload a pdf."
        }
        if($('.filetype input[type=file]').val().length != 0 && div.find('.filetype input[type=text]').val().length != 0){
            valid = false;
            msg = "Only one of the options can be chosen."
        }
    }

    if(!valid){
        div.find('.error.filetype_error').html(msg);
        div.find('.error.filetype_error').css('display', 'block');
    } else{
        div.find('.error.filetype_error').css('display', 'none');
    }

    /* messy way of checking for tags too */
    if (div.find('#answer_tags').length){
        if ($('#answer_tags input:checked').length != 2) {
            valid = false;
            $('#error_tags').html("Select exactly 2 existing tags.");
            $('#error_tags').css('display', 'block');
        } else {
            $('#error_tags').css('display', 'none');
        }
    }


    return valid;
}

/* FEEDBACK FORM */
$(document).ready(function(){
    $('#feedback').on('submit', function(e){
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
