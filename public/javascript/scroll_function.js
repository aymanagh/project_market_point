/* When the user scrolls down, hide the navbar. When the user scrolls up, show the navbar */
var zero = 0;
$(document).ready(function () {
    $(window).on('scroll', function () {
        $('.optionsBar').toggleClass('hide', $(window).scrollTop() > zero);
        zero = $(window).scrollTop();
    })
})