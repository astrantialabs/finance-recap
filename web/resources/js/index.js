require('./bootstrap');
$(function(){
    $("#js-navbar-burger").on("click", function(){
        $(this).toggleClass("is-active");
        $("#js-navbar-menu").toggleClass("is-active");
    });
});