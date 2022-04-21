require('./bootstrap');

// let sideBar = document.querySelector('#js--dashboard-menu');

// sideBar.onclick = () => {
//     sideBar.classList.toggle('collapse');
// };

$(function(){
    $(".fold-table tr.view").on("click", function(){
        $(this).toggleClass("open").next(".fold").toggleClass("open");
    });
    $("#js--dashboard-menu").on("click", function(){
        $(this).toggleClass("collapse");
    });
});