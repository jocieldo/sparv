$(function(){	
	//efeito hover no btn adicionar instrução
    $(".btnHover").hover(function (){
        $(this).attr("src", "images/3.png");
    }).mouseout(function (){
        $(this).attr("src", "images/4.png");
    });

    
    //Script para adicionar labels
    var ids = 1;

    $(".btnNewInst").click(function(){
       
        var novoId = "lb_" + (ids++);
        
        var clone = $("#meuTemplate").clone(true);
        clone.prop("id", novoId); // É necessário mudar o id depois que se clona
        $(".instAssoc:last").after("<br>", clone);
        clone.show();
       
    });

     $(document).on('click', '.botaoExcluir', function() {
		    $(this).closest('label')
		        .prev().remove().end() 
		        .remove();
		});


    //Disparando evento click para criar a primeira label
    $(".btnNewInst").click(); 

	//Ajax para recuperar o texto da instrução passando o ID

	$("input[name='idInstrucao[]']").focusout(function(){

	    var valor = this.value;

	    var self = this;
	    
	    var textArea= $(self).closest('label').find('textarea');

	    $.ajax({
	        url: 'ajax/instrucaoAjax.php',
	        type: 'POST',
	        data: 'idInstrucao='+valor,
	        beforeSend: '',
	        error: function(leitura){
	            alert(leitura);
	        },
	        success: function(leitura){      

	            if(leitura == 1){
	                textArea.val("Esta pergunta não existe!");    
	            }else{
	                textArea.val(leitura);
	            }
	        }
	    });

	});

});