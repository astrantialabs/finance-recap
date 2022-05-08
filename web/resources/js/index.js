require('./bootstrap');
$(function(){
    $("#js-navbar-burger").on("click", function(){
        $(this).toggleClass("is-active");
        $("#js-navbar-menu").toggleClass("is-active");
    });
    $(".fold-table tr.table-view").on("click", function(){
        $(this).toggleClass("open").next(".table-fold").toggleClass("open").toggleClass("is-hidden");
    });
});