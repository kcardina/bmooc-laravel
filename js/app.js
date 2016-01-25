/************
* VALIDATIE *
************/

/**
 * Main validation (abide)
 */
$(document).foundation({
    abide : {
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
                    if (f.size > 2000000) {
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
                    if(div.find('input[type=file]').val().length == 0 && div.find('input[type=text]').val().length == 0){
                        valid = false;
                        msg = "Please enter a link or upload an image."
                    }
                    if($('input[type=file]').val().length != 0 && div.find('input[type=text]').val().length != 0){
                        valid = false;
                        msg = "Only one of the options can be chosen."
                    }
                // video
                } else if(div.find('button#type_video').hasClass('active')){
                    if(div.find('input[type=text]').val().length == 0){
                        valid = false;
                        msg = "Please enter a link to a video on YouTube or Vimeo."
                    }
                // file
                } else if(div.find('button#type_file').hasClass('active')){
                    if(div.find('input[type=file]').val().length == 0 && div.find('input[type=text]').val().length == 0){
                        valid = false;
                        msg = "Please enter a link or upload a pdf."
                    }
                    if($('input[type=file]').val().length != 0 && div.find('input[type=text]').val().length != 0){
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
        parent.find('.input_file').show();
        parent.find('.input_separator').slideDown();
        parent.find('.input_url').slideDown();
        parent.find('.temp_type').val('image');
    } else if ($this.attr('id') == 'type_video') {
        parent.find('.input_url').slideDown();
        parent.find('.temp_type').val('video');
    } else if ($this.attr('id') == 'type_file') {
        parent.find('.input_file').show();
        parent.find('.input_separator').slideDown();
        parent.find('.input_url').slideDown();
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
    $('#newTopicForm').on('valid.fndtn.abide', function(e) {
        console.log('submit');

        // reset & show loading screen
        $('#progress .message').html('Uploading...');
        $('#progress .meter').css('width', '0')
        $('#progress').foundation('reveal', 'open');

        // upload file while
         $.ajax({
             xhr: function() {
                var xhr = new window.XMLHttpRequest();

                 xhr.upload.addEventListener("progress", function(evt) {
                  if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);

                    console.log(percentComplete);
                    $('#progress .meter').css('width', percentComplete + '%')

                    if (percentComplete === 100) {
                        console.log('done');
                    }
                  }
                }, false);
                return xhr;
             },
             type: "POST",
             url: '/topic/new',
             data: $('#newTopicForm').serialize(),
             success: function(result) {
                console.log(result);
             }
         });

        // generate thumbnail on hidden canvas

        // upload thumbnail
    });
});
