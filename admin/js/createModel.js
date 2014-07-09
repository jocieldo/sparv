$(document).ready(function(){

	//Funcão que converte segundos em horas no formato HH:mm:ss
	function convertSecondsInTimes(number){

		//verifica se o parametro (number) passado na função é nulo
		if (number==null || number==""){

			//Sendo a condição acima verdadeira ele não faz nada, simpleste retorna um NULL
			return null;

		}else{

			//calculando os segundos
			var s = Math.round(Math.round(number%3600)%60);

			//calculando minutos
			var m = Math.round(Math.round(number%3600)/60);

			//calculando horas
			var h = Math.round(number/3600);

			//logo abaixo usando três 'if' para formatar o tempo da seguinte forma 'hh:mm:ss'
			if(h < 10){ h = "0"+h; }

			if (m < 10) { m = "0"+m; }
			
			if (s < 10) { s = "0"+s; }

			var hora = h+":"+m+":"+s;

			//retorna um string para ser impressa em qualquer elemento HTML
			return hora;

		}

	}

    $("input[name='idTime']").focusout(function(){

    	var action = 'ajax/tempoAjax.php';
	    var dados = "idTime="+$(this).val();

	    $.get(action, dados, function(datas){

	    	if(datas != '1'){ 

	            if(datas.tempoMomento == '' || datas.tempoMomento == null){
	                datas.tempoMomento = "Nenhum";
	            }

	            $(".lbTime").empty()
	                        .append("<strong>Total:</strong><span> "+convertSecondsInTimes(datas.tempoTotal)+"</span>")
	                        .append("<strong class='margem'>Visibilidade Inicial:</strong><span> "+datas.tempoVisao+"</span>")
	                        .append("<strong class='margem'>Mudando Em:</strong><span> "+datas.tempoMomento+"</span>")
	                        .append("<strong class='margem'>Ordem:</strong><span> "+datas.tempoOrdem+"</span>");

				$("form").submit(true);	   

	        }else if(datas == '1'){

	        	//Escreve um mensagem
	        	$(".lbTime").empty()
	                    .append("<strong>Registro de tempo inexistente!</strong>");

	        }else if(datas == '3'){

	        	$(".lbTime").empty()
	                    .append("<strong>é aqui</strong>");

	        }

	    }, 'json');

    });

});