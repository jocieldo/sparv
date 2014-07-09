$(document).ready(function (){
    $(":submit[name=recuperar_senha]").click(function (){
        $(".alertas").attr("display", "block");
    });
});