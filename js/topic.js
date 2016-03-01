var artefactLeft = null;
var artefactRight = null;

var temp_future = null;
var future = null;

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
        // convert from id to number of answer
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
            $('#artefact_right_buttons').hide();
        }

        displayDiv(result.artefact.type.description, $('#artefact_left_contents'), result.artefact);
        showArrowLeft(result.artefact.parent_id);
        //configAnswer(artefactLeft);
        configNewInstructionPanel(artefactLeft);
        configCurrentInstructionPanel($('#instruction'), artefactLeft.instruction[0]);
    });
}

function showArtefactRight(number_of_answer) {
    temp_future = number_of_answer;
    hideDiv($('#artefact_right_contents'));

    var idRight = artefactLeft.answers[number_of_answer].id;

    $.getJSON(host + "/json/topic/" + idRight, function (result) {
        artefactRight = result;
        if (result.artefact.type != null)
            displayDiv(result.artefact.type.description, $('#artefact_right_contents'), result.artefact);
        else
            displayDiv('text', $('#artefact_right_contents'), result.artefact);

        $('#artefact_right_buttons').show();


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
            div.find('.loader').show();
        });
    } else {
        div.find('.loader').show();
    }
}

function displayDiv(type, div, data) {
    console.log(data);
    if (div.attr("data-reveal-id")) {
        // load metadata
        var lb = div.attr("data-reveal-id");
        $("#" + lb + " .data-title").html(data.title);
        $("#" + lb + " .data-added").html(parseDate(data.created_at));
        $("#" + lb + " .data-author").html("<a href=\""+host+"/search/"+data.the_author.id+ "\">" + data.the_author.name + "</a>");
        if (data.copyright != null) {
            $("#" + lb + " .data-copyright").html(data.copyright);
            $("#" + lb + " .data-copyright").parent().show();
        } else {
            $("#" + lb + " .data-copyright").parent().hide();
        }
        if (data.attachment != null){
            $("#" + lb + " .data-attachment").html("<a href=\""+ host + "/uploads/attachments/" + data.attachment +"\" target=\"_new\">document</a>");
            $("#" + lb + " .data-attachment").parent().show();
        } else {
            $("#" + lb + " .data-attachment").parent().hide();
        }
    }
    if (data.tags) {
        var list = "";
        $.each(data.tags, function (index, value) {
            list += "<li><a href=\""+host+"/search/all/"+value.id+ "\">" + value.tag + "</a></li>\n";
        });
        $("#" + lb + " .data-tags").html(list);
    }

    render(div.parent(), type, data);
    if(lb) render($("#" + lb + " .data-item"), type, data);

}

function showArrowLeft(id) {
    if (id != null) {
        $('#nav_left').html('<a href="#" onclick="showArtefactLeft(' + id + ',null,' + artefactLeft.artefact.id + '); future = temp_future;">&larr;</a>').fadeIn();
    } else
        $('#nav_left').fadeOut().html('');
}

function showArrowUp(id) {
    if (id >= 0) {
        $('#nav_up').html('<a href="#" onclick="showArtefactRight(' + id + '); future = null;">&uarr;</a>').fadeIn();
    } else
        $('#nav_up').fadeOut().html('');
}

function showArrowDown(number_of_answer) {
    if (artefactLeft.answers.length - 1 >= number_of_answer)
        $('#nav_down').html('<a href="#" onclick="showArtefactRight(' + number_of_answer + '); future = null;">&darr;</a>').fadeIn();
    else
        $('#nav_down').fadeOut().html('');
}
function showArrowRight(id) {
    if (id != null) {
        if(future != null){
            $('#nav_right').html('<a href="#" onclick="showArtefactLeft(' + id + ', ' + future + '); future = null;">&rarr;</a>').fadeIn();
        } else {
            $('#nav_right').html('<a href="#" onclick="showArtefactLeft(' + id + ',0 ); ">&rarr;</a>').fadeIn();
        }
    } else
        $('#nav_right').fadeOut().html('');
}

function configAnswer(artefact) {
    //console.log('!!!!!!!!!!!!!!!!!!!!');
    //console.log(artefact);
    // Tags klaarzetten
    $('#answer_tags div').remove();
    $.each(artefact.tags, function (k, tag) {
        $('#answer_tags').prepend('<div class="tag-button purple"><label><input  type="checkbox" data-abide-validator="tag_select" name="answer_tags[]" value="' + tag.id + '"><span>' + tag.tag + '</span></label></div>');
    });

    // Beschikbare antwoordtypes klaarmaken
    $('#answer_button_text').hide();
    $('#answer_button_image').hide();
    $('#answer_button_video').hide();
    $('#answer_button_file').hide();
    if (artefact.instruction.length > 0) {
        $.each(artefact.instruction[0].available_types, function (k, atype) {
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
    $('#answer_parent').val(artefact.artefact.id);
    showInstruction(artefact.instruction[0], true);
}

function showInstruction(instruct, current) {
    //console.log('*********************************');
    //console.log(instruct);
    //console.log(current);
    var prefix = current ? '' : 'new_';
    $('#' + prefix + 'instruction_title').off('click');
    if (instruct) {
        $('button[data-reveal-id="instruction"]').show();
        $('#' + prefix + 'instruction_title').show();
        $('#' + prefix + 'instruction_content').show();
        $('#' + prefix + 'instruction_title').click(function() {
            $('#' + prefix + 'instruction_content').slideToggle(function(){
                if ($(this).is(':visible')) {
                     $('#' + prefix + 'instruction_title').html('&#x25BC; Current instruction');
                } else {
                     $('#' + prefix + 'instruction_title').html('&#x25B6; Current instruction');
                }
            });
        });
        if (instruct.instruction_type)
            render( $('#' + prefix + 'instruction_content'), instruct.instruction_type.description, instruct);
    } else {
        $('button[data-reveal-id="instruction"]').hide();

        //$('#'+prefix+'instruction_title').hide();
        $('#' + prefix + 'instruction_title').hide();
        $('#' + prefix + 'instruction_content').hide();
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
        // available data-types to icon
        var answer_types = document.createElement('ul');
        $(answer_types).addClass('inline slash');
        var ft = filetypesToIcons(data.available_types);

        $.each(ft, function(key, value){
            $(answer_types).append("<li>" + value + "</li>\n");
        });
        $("#" + lb + " .data-answer-types").html(answer_types);
        if (data.tags) {
            var list = "";
            $.each(data.tags, function (index, value) {
                list += "<li><a href=\"#\">" + value.tag + "</a></li>\n";
            });
            $("#" + lb + " .data-tags").html(list);
        }

        render(div, data.instruction_type.description, data);
        if(lb) render($("#" + lb + " .data-item"), data.instruction_type.description, data);

    } else {
        $('#instruction_metadata').html('&nbsp;');
        $("#" + lb + " .data-item").html("No instruction given...");
    }
}


function configNewInstructionPanel(artefact) {
    //console.log(artefact);
    $('#instruction_parent').val(artefact.artefact.thread);
    showInstruction(artefact.instruction[0], false);
}

function filetypesToIcons(f){
    var r = [];
    // get icons
    $.each(f, function(key, value){
        var icon = filetypeToIcon(parseInt(value.id), value.description);
        console.log(r);
        if($.inArray(icon, r) == -1){
            r.push(icon);
        }
    });
    return r;
}

function filetypeToIcon(f, msg){
    switch(f){
        case 28: //text
            return "<i title=\"text\" class=\"fa fa-align-justify\"></i>";
        case 29: //text
            return "<i title=\"image\" class=\"fa fa-camera\"></i>";
        case 30: //text
            return "<i title=\"image\" class=\"fa fa-camera\"></i>";
        case 31: //text
            return "<i title=\"video\" class=\"fa fa-video-camera\"></i>";
        case 32: //text
            return "<i title=\"video\" class=\"fa fa-video-camera\"></i>";
        case 33: //text
            return "<i title=\"file\" class=\"fa fa-file\"></i>";
        case 34: //text
            return "<i title=\"file\" class=\"fa fa-file\"></i>";
        case 37: //text
            return "<i title=\"file\" class=\"fa fa-file\"></i>";
        case 38: //text
            return "<i title=\"file\" class=\"fa fa-file\"></i>";
        default:
            return "";
    }
}

//function loadInstruction(id) {
//    if (!id)
//        id = artefactLeft.id;
//    console.log(artefactLeft);
//    $.getJSON(host + "/json/topic/" + id, function (result) {
//        console.log(result);
//    });
//}
