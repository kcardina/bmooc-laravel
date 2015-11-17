function configAnswer(artefact) {
	// Set the parent of the answer
	$('#parent_id').attr('value', artefact.artefact.id);
	
	// Show the tags in the select field
	var tags = artefact.tags;
	$('#existingtags').find('option').remove();
	$.each(tags, function(key, value) {
		console.log(value);
		$('#existingtags').append($("<option></option>").attr("value",value.id).text(value.tag));
	});
	
	// Show/hide the answer type fields
	if (artefact.instruction.length>0) {
		var instruction = artefact.instruction[0];
		$('#answer_instruction legend').html(instruction.title?'Instruction: ' + instruction.title:'Instruction');
		
		var idiv = $('#answer_instruction #answer_instruction_contents') 
		switch (instruction.instruction_type.description) {
		case 'text':
			idiv.html(instruction.contents);
			break;
		case 'local_image':
			idiv.html('<img src="'+ host + "/uploads/"+instruction.url+'" style="min-height: 100%; min-width: 100%">');
			break;
		case 'remote_image':
			idiv.html('<img src="'+instruction.url+'" style="min-height: 100%; min-width: 100%">');
			break;
		case 'video_youtube':
			idiv.html('<iframe  src="'+instruction.url+'?autoplay=0" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
			break;
		case 'video_vimeo':
			idiv.html('<iframe src="'+instruction.url+'" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
			break;
		}

		$('#answer_text').hide();
		$('#answer_upload').hide();
		$('#answer_url').hide();
		var upload_filters = null;
		var url_filters = null;
		var url_regex = null;
		$.each(instruction.available_types, function(key, value) {
			var atype = value.description;
			switch (atype) {
			case 'text':
				$('#answer_text').show();
				break;
			case 'local_image':
				upload_filters = (upload_filters?upload_filters+'|':'')+'jpg|jpeg|png|gif';
				break;
			case 'local_pdf':
				upload_filters = (upload_filters?upload_filters+'|':'')+'pdf';
				break;
			case 'remote_image':
				url_filters = (url_filters?url_filters+'|':'')+'jpg|jpeg|png|gif';
				url_regex = (url_regex?url_regex+'|':'^')+'(.+\.(jpg|jpeg|png|gif))';
				break;
			case 'remote_pdf':
				url_filters = (url_filters?url_filters+'|':'')+'pdf';
				url_regex = (url_regex?url_regex+'|':'^')+'(.+\.(pdf))';
				break;
			case 'video_youtube':
				url_filters = (url_filters?url_filters+'|':'')+'youtube';
				url_regex = (url_regex?url_regex+'|':'^')+'((https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+)';
				break;
			case 'video_vimeo':
				url_filters = (url_filters?url_filters+'|':'')+'vimeo';
				url_regex = (url_regex?url_regex+'|':'^')+'((https?\:\/\/)?(www\.)?(vimeo\.com)\/.+)';
				break;
			}
		});
		
		url_regex += '$';
		if (upload_filters) {
			$('#answer_upload').show();
			$('#answer_upload label').html('Upload a file ('+ upload_filters+')')
		}
		if (url_filters) {
			$('#answer_url').show();
			$('#answer_url label').html('URL ('+ url_filters+')')
		}
		
		$('#answer_instruction').show();
	} else {
		$('#answer_instruction').hide();
	}
	
	$.validator.addMethod("regex", function(value, element, regexpr) {
		console.log(regexpr);
	    return regexpr.test(value);
	}, "Please provide a correct url.");
	
	$('#commentForm').validate({
		rules: {
			'title': { required: true },
			'tags[]': { required: true, minlength:3 },
			'tagNew1': { required: true },
			'contents': { require_from_group: [1, '.oneRequired'] },
			'url': {
				require_from_group: [1, '.oneRequired'],
				regex: new RegExp(url_regex)
			},
			'artefact_image': { require_from_group: [1, '.oneRequired'], extension: upload_filters }
		},
		messages: {
			'title': { required: 'The title is mandatory' },
			'tags[]': { minlength:"Choose {0} tags from the list" },
			'tagNew1': { required: 'You must add a new tag' },
			'contents': { require_from_group: 'Provide a text or one of the other available options'},
			'url': { require_from_group: 'Provide a URL to the correct document (' + url_filters + ') or one of the other available options'},
			'artefact_image': { require_from_group: 'Provide a correct file (' + upload_filters + ') or one of the other available options'}
		},
		errorPlacement: function(error, element) {
			if ( element.is(":checkbox")) {
				error.appendTo( element.parents('.container') );
			} else { // This is the default behavior
				error.insertAfter( element );
			}
		}
	});
}

$(function() {
	$(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
		var modal = $(this);
		//$('#commentForm').foundation({bindings:'events'});
		$('.tags').bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).autocomplete( "instance" ).menu.active ) {
				event.preventDefault();
			}
		}).autocomplete({
			source: function( request, response ) {
				$.getJSON( "{{ URL::to('tags/') }}" + "/" + extractLast(request.term), null,
					function(result) { $('.ui-autocomplete').addClass('f-dropdown'); response(result)}
				);
			},
			search: function() {
				var term = extractLast( this.value );
				//if ( term.length < 2 ) return false;
			},
			focus: function() { return false; },
			select: function( event, ui ) {
				this.value = ui.item.value;
				return false;
			}
		});
	});
});