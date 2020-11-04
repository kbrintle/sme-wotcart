$(document).ready(function() {
    $('a.navbar-minimalize').click(function() {
        if ($('.navbar-static-side').is(":visible")) {
            $('.navbar-static-side').hide();
            $('#page-wrapper').animate({
                marginLeft: '-=220px'
            }, 220);
        } else {
            $('#page-wrapper').animate({
                marginLeft: '+=220px'
            }, 220, function () {
                $('.navbar-static-side').show();
            });
        }
    });
});