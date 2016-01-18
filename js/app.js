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
                console.log('cheking tags');
                var tags = [];
                var valid = true;
                $('#answer_tags input[type=checkbox]').each(function() {
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
    console.log("checking tag list");
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
