var artefactLeft = null;

function showArtefactLeft(id, answer) {
	if (!answer) answer = 0;
	$('#artefact_left_contents').html('<img class="loader" src="' + host + '/img/loader.gif" alt="antwoorden worden geladen..." style="min-width: 10px; min-height: 10px"/>');
	$.getJSON(host + "/json/topic/" + id, function(result) {
		artefactLeft = result;
		displayDiv(result.artefact.type.description, $('#artefact_left_contents'), result.artefact);
		
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
		
		if (answer != null && result.answers.length > answer) {
			showArtefactRight(answer);
			if (answer > 0) showArrowUp(answer-1);
			showArrowDown(answer+1);
		} else {
			$('#artefact_right_contents').html('');
		}
		showArrowHead(result.artefact.parent_id);
		configAnswer(artefactLeft);
		configNewInstructionPanel(artefactLeft);
	});
}

function showArtefactRight(number_of_answer) {
	var idRight = artefactLeft.answers[number_of_answer].id;
	$('#artefact_right_contents').html('<img class="loader" src="' + host + '/img/loader.gif" alt="antwoorden worden geladen..." style="min-width: 10px; min-height: 10px"/>');
	$.getJSON(host + "/json/topic/" + idRight, function(result) {
		if (result.artefact.type != null) displayDiv(result.artefact.type.description, $('#artefact_right_contents'), result.artefact);
		else displayDiv('text', $('#artefact_right_contents'), result.artefact);
		if (number_of_answer >= 0) showArrowUp(number_of_answer-1);
		showArrowDown(number_of_answer+1);
		if (result.answers.length >0) showArrowRight(idRight);
		else showArrowRight(idRight); // showArrowRight(null);
		window.history.pushState("topichistory", "bMOOC - Topic " + artefactLeft.artefact.id + "/" + number_of_answer, host + "/topic/" + artefactLeft.artefact.id + "/" + number_of_answer);
	});
}

function displayDiv(type, div, data) {
	switch (type) {
	case 'text':
		div.html(data.contents);
		break;
	case 'local_image': 
		div.html('<a href="'+ host + "/uploads/"+data.url+'" data-lightbox="image-1" data-title="Image"><img src="'+ host + "/uploads/"+data.url+'"></a>');
		break;
	case 'remote_image':
		div.html('<a href="'+data.url+'" data-lightbox="image-1" data-title="Image"><img src="'+data.url+'"></a>');
		break;
	case 'video_youtube':
		div.html('<iframe  src="'+data.url+'?autoplay=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		break;
	case 'video_vimeo':
		div.html('<iframe src="'+data.url+'" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		break;
	case 'remote_document':
		div.html('Please, <a href="'+ data.url +'" target="_new">download</a> the document to open...');
		break;
	case 'local_document':
		div.html('Please, <a href="'+ host + "/uploads/" + data.url +'" target="_new">download</a> the document to open...');
		break;
	case 'local_pdf':
		break;
	case 'remote_pdf':
		break;
	}
}

function showArrowHead(head_id) {
	if (head_id != null) {
		$('#arrow_left').html('<a href="#" onclick="showArtefactLeft('+head_id+')" class="nav left">&larr;</a>').show();
	} else $('#arrow_left').hide();
}

function showArrowUp(id) {
	if (id >= 0) {
		$('#arrow_up').html('<a href="#" onclick="showArtefactRight('+id+')" class="nav up" >&uarr;</a>').show();
	} else $('#arrow_up').hide();
}

function showArrowDown(number_of_answer) {
	if (artefactLeft.answers.length-1 >= number_of_answer)
		$('#arrow_down').html('<a href="#" onclick="showArtefactRight('+number_of_answer+')" class="nav down">&darr;</a>').show();
	else $('#arrow_down').hide();
}
function showArrowRight(id) {
	if (id != null) {
		$('#arrow_right').html('<a href="#" onclick="showArtefactLeft('+id+', 0); " class="nav right">&rarr;</a>').show();
	} else $('#arrow_right').hide();
}

function configAnswer(artefact) {
	console.log('!!!!!!!!!!!!!!!!!!!!');
	console.log(artefact);
	// Tags klaarzetten
	$('#answer_tags div').remove();
	$.each(artefact.tags, function(k, tag) {
		$('#answer_tags').append('<div class="tag-button purple"><label><input type="checkbox" name="answer_tags[]" value="'+tag.id+'"><span>'+tag.tag+'</span></label></div>');
	});
	
	// Beschikbare antwoordtypes klaarmaken
	$('#answer_button_text').hide();
	$('#answer_button_video').hide();
	$('#answer_button_file').hide();
	if (artefact.instruction.length > 0) {
		$.each(artefact.instruction[0].available_types, function(k, atype) {
			if (atype.description == 'video_vimeo' || atype.description == 'video_youtube') $('#answer_button_video').show();
			if (atype.description == 'local_image' || atype.description == 'local_document') $('#answer_button_file').show();
			if (atype.description == 'text') $('#answer_button_text').show();
		});
	} else {
		$('#answer_button_video').show();
		$('#answer_button_file').show();
		$('#answer_button_text').show();
	}	
	$('#answer_parent').val(artefact.artefact.id);
	showInstruction(artefact.instruction[0], true);
}

function showAnswerType(e) {
	e.preventDefault();
	var $this = $(this);
	if ($this.attr('id') == 'button_answer_button_text') {
		$('#answer_input_text').slideToggle();
		$('#answer_input_video').hide();
		$('#answer_input_upload').hide();
		$('#answer_temp_type').val('text');
	} else if ($this.attr('id') == 'button_answer_button_video') {
		$('#answer_input_text').hide();
		$('#answer_input_video').slideToggle();
		$('#answer_input_upload').hide();
		$('#answer_temp_type').val('url');
	} else if ($this.attr('id') == 'button_answer_button_file') {
		$('#answer_input_text').hide();
		$('#answer_input_video').hide();
		$('#answer_input_upload').slideToggle();
		$('#answer_temp_type').val('file');
	}

}
function showInstructionType(e) {
	e.preventDefault();
	var $this = $(this);
	if ($this.attr('id') == 'button_answer_button_text') {
		$('#instruction_input_text').slideToggle();
		$('#instruction_input_video').hide();
		$('#instruction_input_upload').hide();
		$('#instruction_temp_type').val('text');
	} else if ($this.attr('id') == 'button_answer_button_video') {
		$('#instruction_input_text').hide();
		$('#instruction_input_video').slideToggle();
		$('#instruction_input_upload').hide();
		$('#instruction_temp_type').val('url');
	} else if ($this.attr('id') == 'button_answer_button_file') {
		$('#instruction_input_text').hide();
		$('#instruction_input_video').hide();
		$('#instruction_input_upload').slideToggle();
		$('#instruction_temp_type').val('file');
	}
}
function showInstruction(instruct, current) {
	console.log('*********************************');
	console.log(instruct);
	console.log(current);
	var prefix = current?'':'new_';
	if (instruct) {
		$('#'+prefix+'instruction_title').html('Current instruction: ' + instruct.title);
		$('#'+prefix+'instruction_title').click(function() {
			$('#'+prefix+'instruction_content').slideToggle();
		});
		if (instruct.instruction_type) displayDiv(instruct.instruction_type.description, $('#'+prefix+'instruction_content'), instruct);
	} else {
		//$('#'+prefix+'instruction_title').hide();
		$('#'+prefix+'instruction_title').html('Current instruction');
	}
}

function configNewInstructionPanel(artefact) {
	console.log(artefact);
	$('#instruction_parent').val(artefact.artefact.thread);
	showInstruction(artefact.instruction[0], false);
}