$(document).ready(function() {
    function scroll_to_down(div) {
        $(div).click(function() {
            var n = $(document).height();
            $('html,body').animate({scrollTop: n}, 50);
        });
        $(window).scroll(function(){
            if($(window).scrollTop()<500){
                $(div).fadeOut();
            } else{
                $(div).fadeIn();
            }
        });
    }
    scroll_to_down("#scroll_to_down");
});


