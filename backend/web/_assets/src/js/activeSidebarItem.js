// Activate the current page in the sidebar
$("#side-menu li a").each(function () {
    var url = window.location.href+"/";
    if (url.includes($(this).prop("href")+"/")) {
        $(this).parent().addClass("active");
    }
});