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
    parent.removeClass('error');
    parent.children().removeClass('error');

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

/* CLEAR FILE UPLOAD */
$(function(){
    $('button .clear').on("click", function(){
        var control = $("#control");
        control.replaceWith( control.val('').clone( true ) );
    });
});

;( function( $, window, document, undefined ){
	$( '.inputfile' ).each( function(){
		var $input	   = $( this ),
			$el  	   = $input.parent( 'label' ),
            $filename  = $el.find('.file_filename');

        $el.find('.file_reset').on('click', function (e){
            e.preventDefault();

            $input.wrap('<form></form>').closest('form').get(0).reset();
            $input.unwrap();
            $input.trigger('change');
        });

		$input.on( 'change', function( e ){
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

            $filename.html( fileName );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
})( jQuery, window, document );


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
            html = '<object data="' + host + "/uploads/" + data.url + '" type="application/pdf"><a href="' + host + "/uploads/" + data.url + '">Click to view PDF</a><br/><small>(Your browser does not support viewing of PDF\'s inside bMOOC)</small></object>';
            break;
        case 'remote_pdf':
            html = '<object data="' + data.url + '" type="application/pdf"><a href="' + data.url + '">Click to view PDF</a><br/><small>(Your browser does not support viewing of PDF\'s inside bMOOC)</small></object>';
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
                img.onerror = function(e){
                    pointer.dfd.reject("Failed to read the image");
                }
                img.src = e.target.result;
            } else if(pointer.file.type.match('application/pdf') || pointer.file.type.match('application/x-pdf')){
                var pdfAsArray = convertDataURIToBinary(e.target.result);
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


/************************************************
* RENDER TREES (dependencies: d3js & 3dplus.js) *
************************************************/

var Tree = (function(){

    /** Static variables shared by all instances **/
    Tree.IMAGE_SIZE = 100;
    Tree.MARGIN = {
        top: Tree.IMAGE_SIZE/2,
        right: Tree.IMAGE_SIZE/2,
        bottom: Tree.IMAGE_SIZE/2,
        left: Tree.IMAGE_SIZE/2
    };
    Tree.TEXTBOUNDS = {
        width: Tree.IMAGE_SIZE,
        height: Tree.IMAGE_SIZE,
        resize: true
    };

    /**
     * Create a Tree.
     * @param {dom element} el - The container for the Tree svg element.
     * @param {JSON} data - A JSON-tree containing the data to visualize.
     * @param {object} opt - An optional object defining the trees behavior
     */
    function Tree(el, data, opt){

        // Options array
        this.options = {
            interactive: true, // Allow dragging & zooming
            showImages: true, // Show the images
            background: true, // give a background to text so the links appear behind
            fit: true // scales the visualisation to fit the container upon render
        };

        if(typeof opt !== 'undefined'){
            if(typeof opt.interactive !== 'undefined') this.options.interactive = opt.interactive;
            if(typeof opt.showImages !== 'undefined') this.options.showImages = opt.showImages;
            if(typeof opt.background !== 'undefined') this.options.background = opt.background;
        }

        this.data = data;
        this.el = el;

        this.zoomListener = d3.behavior.zoom()
            .on("zoom", this.zoomed);
        this.hasZoom = false;

        this.svg = d3.select(this.el).append("svg")
                .attr("width", '100%')
                .attr("height", '100%')
                .attr("class", "svg");
        // add one g to capture events
        this.container = this.svg.append("g")
            .attr("class", "tree_container");
        // add one g to scale
        this.zoomContainer = this.container.append("g")
            .attr("class", "tree_zoom")
        // add another g to draw the visualisation
        this.g = this.zoomContainer.append("g");

        this.width = function(){
            return this.el.getBoundingClientRect().width;
        }
        this.height = function() {
            return this.el.getBoundingClientRect().height;
        }
    }

    /**
     *  Render a visualisation
     */
    Tree.prototype.render = function(type){
        // clear the current vis
        d3.select(this.el).select(".tree_container").remove();
        // add one g to capture events
        this.container = this.svg.insert("g",":first-child")
            .attr("class", "tree_container");
        // add one g to scale
        this.zoomContainer = this.container.append("g")
            .attr("class", "tree_zoom")
        // add another g to draw the visualisation
        this.g = this.zoomContainer.append("g");

        if(type == "tree") this.renderTree();

        if(type == "tags") this.renderTags();

        if(this.options.interactive){
            this.zoomContainer
                .insert("rect",":first-child")
                .attr('class', 'tree_zoom-capture')
                .style('visibility', 'hidden')
                .attr('x', this.g.node().getBBox().x - 25)
                .attr('y', this.g.node().getBBox().y - 25)
                .attr('width', this.g.node().getBBox().width + 50)
                .attr('height', this.g.node().getBBox().height + 50);
            this.container.call(this.zoomListener);
        }

        //if(this.options.fit) this.fit();
    }

    /**
     *  Resize the tree to fit the container
     */
    Tree.prototype.fit = function(){

        width = this.width();
        height = this.height();

        var t = [0,0],
            s = 1,
            w = this.g.node().getBBox().width,
            h = this.g.node().getBBox().height;

        if(w > width) s = width/w;
        if(h > height && height/h < s) s = height/h;

        t_w = width/2 - (w/2)*s;
        t_h = -this.g.node().getBBox().y*s + (height-h*s)/2

        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .scaleExtent([s, 1]);
        // for some mysterious reason, updation the zoomListener only works when called twice
        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .scaleExtent([s, 1]);

        this.svg.transition()
            .duration(125)
            .call(this.zoomListener.event);

        if(s != 1){
            this.hasZoom = true;
        }
    }

    /**
     * scale+move when window is resized
     */
    Tree.prototype.resize = function(){

        width = this.width();
        height = this.height();

        var t = [0,0],
            s = this.zoomListener.scale(),
            w = this.g.node().getBBox().width,
            h = this.g.node().getBBox().height;

        t_w = width/2 - (w/2)*s;
        t_h = -this.g.node().getBBox().y*s + (height-h*s)/2

        this.zoomListener
            .scale(s)
            .translate([t_w, t_h])
            .event(d3.select(this.el));

        if(s != 1){
            this.hasZoom = true;
        }
    }

    /**
     *  Zoom programatically
     */
    Tree.prototype.zoom = function(z){
        this.zoomListener
            .scale(this.zoomListener.scale() + z)
            .event(d3.select(this.el));
    }

    /**
     * Zoom and pan
     * some interesting hints here: http://stackoverflow.com/questions/17405638/d3-js-zooming-and-panning-a-collapsible-tree-diagram
     * It's important that this.zoomListener has been updated in the resize function
     */
    Tree.prototype.zoomed = function(){
        // this should be this.container g element
        d3.select(this).select('.tree_zoom').attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
        d3.select(window).on("mouseup.zoom", function(){
            d3.select(window).on("mousemove.zoom", null).on("mouseup.zoom", null);
        });
    }

    /**
     * Generate and show the tree.
     */
    Tree.prototype.renderTree = function(){

        treelayout = d3.layout.tree()
            .nodeSize([Tree.IMAGE_SIZE, Tree.IMAGE_SIZE]);

        // Compute the new tree layout.
        var nodes = treelayout.nodes(this.data);//.reverse()
        var links = treelayout.links(nodes);

        // horizontal spacing of the nodes (depth of the node * x)
        nodes.forEach(function(d) { d.y = d.depth * (Tree.IMAGE_SIZE + Tree.IMAGE_SIZE/10) });

        // Declare the nodes.
        var node = this.g.selectAll("g.node")
            .data(nodes);

        // Draw the links
        this.drawNodes(node);

        // Declare the links
        var link = this.g.selectAll("path.link")
        .data(links, function(d) { return d.target.id; });

        var diagonal = d3.svg.diagonal()
            .projection(function(d) { return [d.y, d.x]; });

        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", diagonal);
    }

    /**
     *  Backwards compatibility
     */
    Tree.prototype.draw = function(){
        this.render("tree");
    }

    /**
     * Draw the nodes. Reused by the render functions
     */
    Tree.prototype.drawNodes = function(node){

        // Enter the nodes.
        var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) {
                return "translate(" + d.y + "," + d.x + ")";
            });

        if(this.options.showImages){

            //hidden
            nodeEnter.filter(function(d) { return d.hidden; })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/topic/"+d.id;
                })
                .append("circle")
                .attr("cx", 5)
                .attr("cy", 0)
                .attr("r", 5);

            //img
            nodeEnter.filter(function(d) { return d.url; })
                .filter(function(d) { return !d.hidden })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/topic/"+d.id;
                })
                .append("image")
                .attr("xlink:href", function(d) {
                    return "/artefact/" + d.id + "/thumbnail/"
                })
                .attr('y', -Tree.IMAGE_SIZE/2)
                .attr('width', Tree.IMAGE_SIZE)
                .attr('height', Tree.IMAGE_SIZE);

            //text
            var bg = nodeEnter.filter(function(d) { return d.contents })
                .filter(function(d) { return !d.hidden })
                .append("rect")
                .attr('width', Tree.IMAGE_SIZE)
                .attr('height', Tree.IMAGE_SIZE)
                .attr('y', -Tree.IMAGE_SIZE/2);
            if(this.options.background) bg.attr("class", "filled");

            nodeEnter.filter(function(d) { return d.contents })
                .filter(function(d) { return !d.hidden })
                .append("a")
                .attr("xlink:href", function(d) {
                    return "/topic/"+d.id;
                })
                .append("text")
                .attr('y', -Tree.IMAGE_SIZE/2)
                .text(function(d) { return splitString(d.title); })
                .each(function(d){
                    d3plus.textwrap()
                        .config(Tree.TEXTBOUNDS)
                        .valign('middle')
                        .align('center')
                        .container(d3.select(this))
                        .draw();
                });
        } else {
            nodeEnter.append("a")
                .attr("xlink:href", function(d) {
                    return "/topic/"+d.id;
                })
                .append("circle")
                .attr("cx", 2.5)
                .attr("cy", 0)
                .attr("r", 5);
        }
    }

    return Tree;

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

function splitString(str) {
    return str.replace(/(\w{12})(?=.)/g, '$1 ');
    //return str.replace(/[^A-Za-z0-9]/, ' ');
    //return str.replace(/\W+/g, " ")
}
