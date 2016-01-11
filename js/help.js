$(document).ready(function(){

    var help = false;

    $("[help-show]").click(function(){
        show();
    });

    function show(){
        $("[data-help]").addClass('help');

        // disable modals
        $("[data-reveal-id]").each(function(){
            var d = $(this).attr("data-reveal-id");
            $(this).removeAttr("data-reveal-id");
            $(this).attr("data-rev-id", d);
        });

        // create bg
        var div_bg = document.createElement("div");
        div_bg.setAttribute('class', 'help-bg');
        div_bg.innerHTML = "<a class=\"help-close\" aria-label=\"Close\">×</a><div class=\"help-txt\">Click an item to get help.</div>";
        $("body").append(div_bg);
        $(".help-close").on('click', hide);
        $(".help-bg").fadeIn();

        // create msg
        var div_msg = document.createElement("div");
        var close = document.createElement("a");
        var msg_content = document.createElement("div");
        $(close).addClass('help-msg-close');
        $(close).html('×');
        $(close).on('click', function(){
            $(div_msg).fadeOut();
        });
        $(div_msg).append(close);
        $(msg_content).addClass('msg-content');
        $(div_msg).append(msg_content);
        $(div_msg).addClass('help-msg');
        $(div_msg).css('position', 'absolute');
        $(div_msg).css('z-index', '9999');
        $(div_msg).css('display', 'none');
        $('body').append(div_msg);

        $("[data-help]").bind('click', showMessage);

    }

    function hide(){
        $('.help-msg').fadeOut(function(){
            $('.help-msg').remove();
        });

        // enable modals
        $("[data-rev-id]").each(function(){
            var d = $(this).attr("data-rev-id");
            $(this).attr("data-rev-id");
            $(this).attr("data-reveal-id", d);
        });

        $(".help-bg").fadeOut(function(){
            $(this).remove();
            $("[data-help]").removeClass('help');
            $(document).foundation();
        });
    }

    function showMessage(e){
        e.preventDefault();
        $('.help-msg').hide();

        $('.help-msg').css('top', $(this).offset().top + $(this).height() + 10 + 'px');
        $('.help-msg').css('left', $(this).offset().left + 'px');
        $('.msg-content').html($(this).data('help'));
        $('.help-msg').fadeIn();
    }

});
