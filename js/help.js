$(document).ready(function(){

    var help = false;

    $("[help-show]").click(function(){
        show();
    });

    function show(){
        $("[data-help]").addClass('help');
        var div = document.createElement("div");
        div.setAttribute('class', 'help-bg');
        div.innerHTML = "<a class=\"help-close\" aria-label=\"Close\">×</a>";
        $("body").append(div);
        $(".help-close").on('click', hide);
        $(".help-bg").fadeIn();

        $("[data-help]").bind('click', showMessage);

    }

    function hide(){
        $(".help-bg").fadeOut(function(){
            $(this).remove();
            $("[data-help]").removeClass('help');
            $(document).foundation();
        });
    }

    function showMessage(e){
        e.preventDefault();
        console.log($(this).data('help'));
        var div = document.createElement("div");
        var close = document.createElement("a");
        $(close).addClass('help-msg-close');
        $(close).html('×');
        $(close).on('click', function(){
            $(div).fadeOut();
        });
        $(div).append(close);
        $(div).append($(this).data('help'));
        console.log($(this).offset());
        $(div).addClass('help-msg');
        $(div).css('position', 'absolute');
        $(div).css('z-index', '9999');
        $(div).css('display', 'none');
        $(div).css('top', $(this).offset().top + $(this).height() + 10 + 'px');
        $(div).css('left', $(this).offset().left + 'px');
        $('body').append(div);
        $(div).fadeIn();
    }

});
