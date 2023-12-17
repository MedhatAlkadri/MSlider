
jQuery(document).ready(function ($) {
    $('.my-slider li:gt(0)').hide();
    setInterval(function () {
        $('.my-slider li:first').fadeOut(1000, function () {
            $(this).next().fadeIn(1000).end().appendTo('.my-slider');
        });
    }, 3000);
});
