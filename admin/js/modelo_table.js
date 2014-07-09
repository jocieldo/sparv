$(document).ready(function (){
    $("tbody tr:not(.titleModel)").hide();
    $(".titleModel th").prepend("<img src='images/mais.png' class='maismenos' />");
    $("img").click(function (){
        if ($(this).attr("src") == "images/menos.png"){
            $(this).attr("src", "images/mais.png")
            .parents().siblings("tr").hide();
        }else{
            $(this).attr("src", "images/menos.png")
            .parents().siblings("tr").show();
        }
    });
});