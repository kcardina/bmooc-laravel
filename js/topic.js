var artefactLeft = null;
var artefactRight = null;

function showArtefactLeft(id, answer, prev) {
    if (!answer)
        answer = 0;

    if (answer != null) {
        hideDiv($('#artefact_left_contents'));
        hideDiv($('#artefact_right_contents'));
    } else {
        hideDiv($('#artefact_left_contents'));
    }

    $.getJSON(host + "/json/topic/" + id, function (result) {
        artefactLeft = result;
        // make sure to go back to the right answer when using the left arrow
        if (prev != null) {
            for (var i = 0; i < result.answers.length; i++) {
                if (result.answers[i].id == prev)
                    answer = i;
            }
        }
        // immediatly load right
        if (answer != null && result.answers.length > answer) {
            showArtefactRight(answer);
            if (answer > 0)
                showArrowUp(answer - 1);
            showArrowDown(answer + 1);
        } else {
            // if there is no right
            // show no arrows, but this behaves a bit weird
            showArrowDown(0);
            showArrowRight();
            showArrowUp(-1);
            $('#artefact_right_loader').hide();
        }

        displayDiv(result.artefact.type.description, $('#artefact_left_contents'), result.artefact);
        showArrowLeft(result.artefact.parent_id);
        //configAnswer(artefactLeft);
        configNewInstructionPanel(artefactLeft);
        configCurrentInstructionPanel($('#instruction'), artefactLeft.instruction[0]); // Panel for current instruction

        /*
         var al = artefactLeft.artefact;
         $('#artefact_left_title').html(al.title);
         if (al.title != null) $('#artefact_left_description').html(al.contents);
         if (al.last_modifier) $('#artefact_left_last_author').html('<a href="' + host + '/search/author/'+al.last_modifier.id+'">'+al.last_modifier.name+'</a>');
         else $('#artefact_left_last_author').html('<a href="' + host + '/search/author/'+al.the_author.id+'">'+al.the_author.name+'</a>');
         if (al.the_author) $('#artefact_left_author').html('<a href="' + host + '/search/author/'+al.the_author.id+'">'+al.the_author.name+'</a>');
         $('#artefact_left_tags li').remove();
         $.each(al.tags, function(k, v) {
         $('#artefact_left_tags').append('<li><a href="' + host + '/search/'+v.id+'">'+v.tag+'</a></li>');
         });
         
         $('#artefact_left_related li').remove();
         $.each(artefactLeft.related, function(k, v) {
         $('#artefact_left_related').append('<li><a href="' + host + '/topic/'+v.id+'">'+v.title+'</a></li>');
         });
         */

        /*if (answer != null && result.answers.length > answer) {
         showArtefactRight(answer);
         if (answer > 0) showArrowUp(answer-1);
         showArrowDown(answer+1);
         } else {
         $('#artefact_right_contents').fadeOut();
         $('#nav_up').fadeOut();
         $('#nav_right').fadeOut();
         $('#nav_down').fadeOut();
         }*/
    });
}

function showArtefactRight(number_of_answer) {
    hideDiv($('#artefact_right_contents'));

    var idRight = artefactLeft.answers[number_of_answer].id;

    $.getJSON(host + "/json/topic/" + idRight, function (result) {
        artefactRight = result;
        if (result.artefact.type != null)
            displayDiv(result.artefact.type.description, $('#artefact_right_contents'), result.artefact);
        else
            displayDiv('text', $('#artefact_right_contents'), result.artefact);

        if (number_of_answer >= 0)
            showArrowUp(number_of_answer - 1);
        showArrowDown(number_of_answer + 1);

        if (result.answers.length > 0)
            showArrowRight(idRight);
        else
            showArrowRight(); // showArrowRight(null);

        window.history.pushState("topichistory", "bMOOC - Topic " + artefactLeft.artefact.id + "/" + number_of_answer, host + "/topic/" + artefactLeft.artefact.id + "/" + number_of_answer);
    });
}

/*
 <!-- IMAGE -->
 <!-- <img src="../../../img/tests/hoog.png" />
 
 <!-- VIDEO (youtube) -->
 <!-- <iframe src="https://www.youtube.com/embed/Kn5LXZv11ww" frameborder="0" allowfullscreen></iframe>
 
 <!-- VIDEO (vimeo) -->
 <!--<iframe src="https://player.vimeo.com/video/119343870?byline=0&portrait=0&title=0" frameborder="0"  allowfullscreen></iframe>
 
 <!-- PDF -->
 <!-- <object data="../../../uploads/pdftest.pdf" type="application/pdf"><a href="../../../uploads/pdftest.pdf">[PDF]</a></object>
 
 <!-- TEXT -->
 <div class="textContainer">
 <div class="text">
 <h2>De verengeling van het leven</h2>
 <p>tekst</p>
 </div>
 </div>
 */

function hideDiv(div) {
    if (div.is(":visible")) {
        div.fadeOut(function () {
            $('.artefact.loader', div.parent()).show();
        });
    } else {
        $('.artefact.loader', div.parent()).show();
    }
}

function displayDiv(type, div, data) {
    div.html("");
    var loadImg = false;
    if (div.attr("data-reveal-id")) {
        // load metadata
        var lb = div.attr("data-reveal-id");
        $("#" + lb + " .data-title").html(data.title);
        $("#" + lb + " .data-added").html(parseDate(data.created_at));
        $("#" + lb + " .data-author").html("<a href=\"#\">" + data.the_author.name + "</a>");
        if (data.attachment && data.attachment != null) $("#" + lb + " .data-attachment").html("<a href=\""+ host + "/uploads/attachments/" + data.attachment +"\" target=\"_new\">document</a>");
        else $("#" + lb + " .data-attachment").html("No attachment");
    }
    if (data.tags) {
        var list = "";
        $.each(data.tags, function (index, value) {
            list += "<li><a href=\"#\">" + value.tag + "</a></li>\n";
        });
        $("#" + lb + " .data-tags").html(list);
    }
    var html;
    // load content
    switch (type) {
        case 'text':
            html = "<div class=\"textContainer\"><div class=\"text\"><h2>" + data.title + "</h2>" + data.contents + "</div></div>";
            break;
        case 'local_image':
            html = '<a href="' + host + "/uploads/" + data.url + '" data-lightbox="image-1" data-title="Image"><img src="' + host + "/uploads/" + data.url + '"></a>';
            loadImg = true;
            break;
        case 'remote_image':
            html = '<a href="' + data.url + '" data-lightbox="image-1" data-title="Image"><img src="' + data.url + '"></a>';
            loadImg = true;
            break;
        case 'video_youtube':
            html = '<iframe  id="ytplayer" src="' + data.url + '?autoplay=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 'video_vimeo':
            html = '<iframe src="' + data.url + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;
        case 'local_pdf':
            html = '<object data="' + host + "/uploads/" + data.url + '" type="application/pdf"><a href="' + host + "/uploads/" + data.url + '">[PDF]</a></object>';
            break;
        case 'remote_pdf':
            html = '<object data="' + data.url + '" type="application/pdf"><a href="' + data.url + '">[PDF]</a></object>';
            break;
    }
    div.html(html);

    if (lb) $("#" + lb + " .data-item").html(html);

    if (loadImg) {
        div.imagesLoaded(function () {
            div.stop(true, true)
            $('.artefact.loader', div.parent()).hide();
            div.fadeIn();
        });
    } else {
        div.stop(true, true)
        $('.artefact.loader', div.parent()).hide();
        div.fadeIn();
    }
}

function showArrowLeft(id) {
    if (id != null) {
        $('#nav_left').html('<a href="#" onclick="showArtefactLeft(' + id + ',null,' + artefactLeft.artefact.id + ')">&larr;</a>').fadeIn();
    } else
        $('#nav_left').fadeOut().html('');
}

function showArrowUp(id) {
    if (id >= 0) {
        $('#nav_up').html('<a href="#" onclick="showArtefactRight(' + id + ')">&uarr;</a>').fadeIn();
    } else
        $('#nav_up').fadeOut().html('');
}

function showArrowDown(number_of_answer) {
    if (artefactLeft.answers.length - 1 >= number_of_answer)
        $('#nav_down').html('<a href="#" onclick="showArtefactRight(' + number_of_answer + ')">&darr;</a>').fadeIn();
    else
        $('#nav_down').fadeOut().html('');
}
function showArrowRight(id) {
    if (id != null) {
        $('#nav_right').html('<a href="#" onclick="showArtefactLeft(' + id + ', 0); ">&rarr;</a>').fadeIn();
    } else
        $('#nav_right').fadeOut().html('');
}

function configAnswer(artefact) {
    //console.log('!!!!!!!!!!!!!!!!!!!!');
    //console.log(artefact);
    // Tags klaarzetten
    $('#answer_tags div').remove();
    $.each(artefact.tags, function (k, tag) {
        $('#answer_tags').append('<div class="tag-button purple"><label><input type="checkbox" name="answer_tags[]" value="' + tag.id + '"><span>' + tag.tag + '</span></label></div>');
    });

    // Beschikbare antwoordtypes klaarmaken
    $('#answer_button_text').hide();
    $('#answer_button_image').hide();
    $('#answer_button_video').hide();
    $('#answer_button_file').hide();
    if (artefact.instruction.length > 0) {
        $.each(artefact.instruction[0].available_types, function (k, atype) {
            console.log(k);
            console.log(atype);
            if (atype.description == 'video_vimeo' || atype.description == 'video_youtube')
                $('#answer_button_video').show();
            if (atype.description == 'local_image' || atype.description == 'remote_image')
                $('#answer_button_image').show();
            if (atype.description == 'local_pdf' || atype.description == 'remote_pdf')
                $('#answer_button_file').show();
            if (atype.description == 'text')
                $('#answer_button_text').show();
        });
    } else {
        $('#answer_button_video').show();
        $('#answer_button_image').show();
        $('#answer_button_file').show();
        $('#answer_button_text').show();
    }
    console.log(artefact);
    $('#answer_parent').val(artefact.artefact.id);
    showInstruction(artefact.instruction[0], true);
}

function showAnswerType(e) {
    e.preventDefault();
    var $this = $(this);
    $('.error.answer_input').hide();
    if ($this.hasClass('active')) {
        return false;
    }
    $('.type_select').removeClass('active');
    $this.addClass('active');
    $('.type_input').hide();
    if ($this.attr('id') == 'type_text') {
        $('#answer_input_text').slideDown();
        $('#answer_temp_type').val('text');
    } else if ($this.attr('id') == 'type_image') {
        $('#answer_input_upload').show();
        $('#answer_input_or').slideDown();
        $('#answer_input_url').slideDown();
        $('#answer_temp_type').val('image');
    } else if ($this.attr('id') == 'type_video') {
        $('#answer_input_url').slideDown();
        $('#answer_temp_type').val('video');
    } else if ($this.attr('id') == 'type_file') {
        $('#answer_input_upload').show();
        $('#answer_input_or').slideDown();
        $('#answer_input_url').slideDown();
        $('#answer_temp_type').val('file');
    }
}
function showInstructionType(e) {
    e.preventDefault();
    var $this = $(this);
    $('.error.instruction_input').hide();
    if ($this.hasClass('active')) {
        return false;
    }
    $('.type_select').removeClass('active');
    $this.addClass('active');
    $('.type_input').hide();
    if ($this.attr('id') == 'type_text') {
        $('#instruction_input_text').slideDown();
        $('#instruction_temp_type').val('text');
    } else if ($this.attr('id') == 'type_image') {
        $('#instruction_input_upload').show();
        $('#instruction_input_or').slideDown();
        $('#instruction_input_url').slideDown();
        $('#instruction_temp_type').val('image');
    } else if ($this.attr('id') == 'type_video') {
        $('#instruction_input_url').slideDown();
        $('#instruction_temp_type').val('video');
    } else if ($this.attr('id') == 'type_file') {
        $('#instruction_input_upload').show();
        $('#instruction_input_or').slideDown();
        $('#instruction_input_url').slideDown();
        $('#instruction_temp_type').val('file');
    }
}
function showInstruction(instruct, current) {
    //console.log('*********************************');
    //console.log(instruct);
    //console.log(current);
    var prefix = current ? '' : 'new_';
    $('#' + prefix + 'instruction_title').off('click');
    if (instruct) {
        $('#' + prefix + 'instruction_title').html('Current instruction: ' + instruct.title);
        $('#' + prefix + 'instruction_title').click(function() {
            $('#' + prefix + 'instruction_content').slideToggle();
        });
        if (instruct.instruction_type)
            displayDiv(instruct.instruction_type.description, $('#' + prefix + 'instruction_content'), instruct);
    } else {
        //$('#'+prefix+'instruction_title').hide();
        $('#' + prefix + 'instruction_title').html('Current instruction');
        $('#' + prefix + 'instruction_content').html('No specific instruction given');
    }
}

function configCurrentInstructionPanel(div, data) {
    //div.html("");
    var loadImg = false;
    lb = div.attr('id');
    if (data !== undefined && data!==null) {
        $("#" + lb + " .data-title").html(data.title);
        $("#" + lb + " .data-added").html(parseDate(data.created_at));
        $("#" + lb + " .data-author").html("<a href=\"#\">" + data.the_author.name + "</a>");
        if (data.tags) {
            var list = "";
            $.each(data.tags, function (index, value) {
                list += "<li><a href=\"#\">" + value.tag + "</a></li>\n";
            });
            $("#" + lb + " .data-tags").html(list);
        }
        var html;
        // load content
        switch (data.instruction_type.description) {
            case 'text':
                html = "<div class=\"textContainer\"><div class=\"text\"><h2>" + data.title + "</h2>" + data.contents + "</div></div>";
                break;
            case 'local_image':
                html = '<img src="' + host + "/uploads/" + data.url + '">';
                loadImg = true;
                break;
            case 'remote_image':
                html = '<img src="' + data.url + '">';
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
        }

        $("#" + lb + " .data-item").html(html);
    } else {
        $('#instruction_metadata').html('&nbsp;');
        $("#" + lb + " .data-item").html("No instruction given...");
    }
    if (loadImg) {
        div.imagesLoaded(function () {
            div.stop(true, true)
            $('.artefact.loader', div.parent()).hide();
            div.fadeIn();
        });
    } else {
        div.stop(true, true)
        $('.artefact.loader', div.parent()).hide();
        div.fadeIn();
    }
}


function configNewInstructionPanel(artefact) {
    //console.log(artefact);
    $('#instruction_parent').val(artefact.artefact.thread);
    showInstruction(artefact.instruction[0], false);
}

function parseDate(d) {
    d = d.replace(/-/g, "/");
    d = d.substring(0, d.length - 3);
    return d;
}

//function loadInstruction(id) {
//    if (!id)
//        id = artefactLeft.id;
//    console.log(artefactLeft);
//    $.getJSON(host + "/json/topic/" + id, function (result) {
//        console.log(result);
//    });
//}

function validation() {
    var valid = true;
    var msg;
    if (!$('.type_select').hasClass('active')) {
        valid = false;
        msg = "Please choose one of the file types."
    }
    if ($('button#type_text').hasClass('active')) {
        if ($('#answer_input_text textarea').val().length == 0 || $('#answer_input_text textarea').val() == "Type your topic text here...") {
            valid = false;
            msg = "Please enter some text."
        }
    } else if ($('button#type_image').hasClass('active')) {
        if ($('#answer_upload').val().length == 0 && $('#answer_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link or select a file."
        }
        if ($('#answer_upload').val().length != 0 && $('#answer_url').val().length != 0) {
            valid = false;
            msg = "Only one of the options can be chosen."
        }
        if ($('#answer_upload').val().length != 0) {
            var f = $('#answer_upload')[0].files[0];
            if (f.size > 2000000) {
                msg = "The document is too large (> 2MB)";
                valid = false;
            }
        }
    } else if ($('button#type_video').hasClass('active')) {
        if ($('#answer_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link to a video on YouTube or Vimeo."
        }
    } else if ($('button#type_file').hasClass('active')) {
        if ($('#answer_upload').val().length == 0 && $('#answer_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link or select a pdf document."
        }
        if ($('#answer_upload').val().length != 0 && $('#answer_url').val().length != 0) {
            valid = false;
            msg = "Only one of the options can be chosen."
        }
        if ($('#answer_upload').val().length != 0) {
            var f = $('#answer_upload')[0].files[0];
            if (f.size > 2000000) {
                msg = "The document is too large (> 2MB)";
                var fo = $('#answer_upload')
                fo.replaceWith(fo = fo.clone(true));
                valid = false;
            }
        }
    }
    if ($('#attachment').val().length != 0) {
        var f = $('#attachment')[0].files[0];
        if (f.size > 2000000) {
            msg = "The attached document is too large (> 2MB)";
            valid = false;
            var fo = $('#attachment')
            fo.replaceWith(fo = fo.clone(true));
        }
    }

    if ($('#answer_tags input:checked').length != 2) {
        valid = false;
        $('#error_tags').html("Select exactly 2 existing tags.");
        $('#error_tags').css('display', 'block');
    } else {
        $('#error_tags').css('display', 'none');
    }

    if (!valid) {
        $('.error.answer_input').html(msg);
        $('.error.answer_input').css('display', 'block');
    } else {
        $('.error.answer_input').css('display', 'none');
    }
    return valid;
}
function instruction_validation() {
    var valid = true;
    var msg;
    if (!$('.type_select').hasClass('active')) {
        valid = false;
        msg = "Please choose one of the file types."
    }
    if ($('button#type_text').hasClass('active')) {
        if ($('#instruction_input_text textarea').val().length == 0 || $('#instruction_input_text textarea').val() == "Type your instruction text here...") {
            valid = false;
            msg = "Please enter some text."
        }
    } else if ($('button#type_image').hasClass('active')) {
        if ($('#instruction_upload').val().length == 0 && $('#instruction_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link or select a file."
        }
        if ($('#instruction_upload').val().length != 0 && $('#instruction_url').val().length != 0) {
            valid = false;
            msg = "Only one of the options can be chosen."
        }
        if ($('#instruction_upload').val().length != 0) {
            var f = $('#instruction_upload')[0].files[0];
            if (f.size > 2000000) {
                msg = "The document is too large (> 2MB)";
                valid = false;
            }
        }
    } else if ($('button#type_video').hasClass('active')) {
        if ($('#instruction_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link to a video on YouTube or Vimeo."
        }
    } else if ($('button#type_file').hasClass('active')) {
        if ($('#instruction_upload').val().length == 0 && $('#instruction_url').val().length == 0) {
            valid = false;
            msg = "Please enter a link or select a pdf document."
        }
        if ($('#instruction_upload').val().length != 0 && $('#instruction_url').val().length != 0) {
            valid = false;
            msg = "Only one of the options can be chosen."
        }
        if ($('#instruction_upload').val().length != 0) {
            var f = $('#instruction_upload')[0].files[0];
            if (f.size > 2000000) {
                msg = "The document is too large (> 2MB)";
                var fo = $('#instruction_upload')
                fo.replaceWith(fo = fo.clone(true));
                valid = false;
            }
        }
    }

    if ($('#instruction_types input:checked').length < 1) {
        valid = false;
        //$('#error_types').html("Select at least 1 of these types.");
        $('#error_types').css('display', 'block');
    } else {
        $('#error_types').css('display', 'none');
    }

    if (!valid) {
        $('.error.instruction_input').html(msg);
        $('.error.instruction_input').css('display', 'block');
    } else {
        $('.error.instruction_input').css('display', 'none');
    }
    return valid;
}
