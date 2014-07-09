$(document).ready(function (){
    
    $(".btn_cadastrar").css("background", "#70909D")
                       .css("font", "13px Arial, Helvetica, sans-serif")
                       .mouseover(function (){
                           $(this).css("background", "#4E6772");
                       })
                       .mouseout(function (){
                           $(this).css("background", "#70909D");
                       });
                       
    $("#dataNascimento").mask("99/99/9999", {placeholder: " "});
    
    $("#form_cadastro").validate({
        rules:{
            nome:{required: true, minlength: 3},
            sobrenome:{required: true, minlength: 5},
            dataNascimento:{required: true, minlength: 10, maxlength: 10},
            email:{required: true, email: true},
            senha:{required: true, minlength: 6, maxlength: 8},
        },
        
        messages:{
            nome:{minlength: "Seu nome no mínimo deve conter 20 caracteres*"},
            apelido:{minlength: "Seu sobrenome deve possuir no minimo 5 caracteres*"},
            dataNascimento: {maxlength: "A data deve conter exatamente 10 caracteres no formato (31/09/2013). Insira somente os números*", minlength: "A data deve conter exatamente 10 caracteres no formato (31/09/2013). Insira somente os números*"},
            email:{email: "Insira um e-mail valido. Ex: usuario@dominio.com.br*"},
            senha:{minlength: "Sua senha deve conter no minimo 6 caracteres e o máximo 8*", maxlength: "Sua senha deve conter no minimo 6 caracteres e o máximo 8*"},
        },
    });
});