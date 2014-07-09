$(document).ready(function (){
    $("#form_cadastro").validate({
        
        rules:{
            nome:{required: true}
        }
        
    });
});