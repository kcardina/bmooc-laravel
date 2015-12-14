function visualize(data, el){
    var html = recurse(data, "");
    if(html.length == 0){
        //force at least one thing to click on
        html = "<table><tr><td><a href=\"" + host + "/topic/" + data.id + "\"><span data-answers=\"0\">&nbsp;<span></a></td>";
    }
    el.html(html);
    render();
}

function recurse(d, r){
    var r = '';
    if(d.answers.length > 0){
        r += "<table><tr>";
        r += "<td><a href=\"" + host + "/topic/" + d.id + "\"><span data-answers=\"" + d.answers.length + "\">&nbsp;<span></a></td>";
        r += "<td>";
        $.each(d.answers, function(index, value){
            r += recurse(value);
        });
        r += "</td>";
        r += "</tr></table>";
    }
    return r;
}

function render(){
    /* Style the heatmap */
    // get the heighest value of data-answers
    var e = 'span[data-answers]';
    var max = minMax(e).max;

    var SIZE = false;
    var COLOR = true;
    var BORDER = false;

    $(e).each(function(){
        if(COLOR){
            $(this).css('background-color', 'hsla(123, 80%, 45%, ' + 1 / max * $(this).attr('data-answers') + ')');
            if($(this).attr('data-answers') == 0){
                $(this).css('border-color', 'hsla(123, 80%, 45%, 1)');
            }
        }
        if(SIZE){
            $(this).css('width', 2 / max * $(this).attr('data-answers') + 'rem');
            $(this).css('height', 2 / max * $(this).attr('data-answers') + 'rem');
        }
        if(BORDER){
            $(this).css('border-color', 'hsla(123, 80%, 45%, ' + 1 / max * $(this).attr('data-answers') + ')');
        }
    });

    //positioning
    $(e).each(function(){
        $(this).css('margin-top', '-' + $(this).height()/2);
    });

    /* helper function */
    function minMax(selector) {
      var min=null, max=null;
      $(selector).each(function() {
        var i = parseInt($(this).attr('data-answers') , 10);
        if ((min===null) || (i < min)) { min = i; }
        if ((max===null) || (i > max)) { max = i; }
      });
      return {min:min, max:max};
    }
}
