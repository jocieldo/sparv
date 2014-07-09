$(function(){	

	$("input[name='tfInstrucao']").focusout(function(){

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