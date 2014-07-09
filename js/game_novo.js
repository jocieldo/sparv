var seconds = 0;
var pastSeconds = 0;
var timePast = "";
var countdownTimer = "";
var relogioInvertido = false;

var existeRegraTempo = false;
var regraTempoAtiva = false;
var existeRegraMovimento = false;
var regraMovimentoAtiva = false;
var tempoRegra = -1;
var pontuacaoTipo = 0;
			

// Here is a VERY basic generic trigger method
function triggerEvent(el, type)
{
	if ((el[type] || false) && typeof el[type] == 'function')
	{
		el[type](el);
	}
}
			
//Troca entre as instruções
function hideShowIntructions(divSumir,divMostrar)
{
	document.getElementById(divSumir).style.display  = 'none'; 
	
	if(divMostrar != "START")
		document.getElementById(divMostrar).style.display  = 'block'; 
	else{
		document.getElementById("game").style.display  = 'block'; 
		countdownTimer = setInterval('secondPassed()', 1000);
		if(relogioInvertido == false)
			LoadTime(seconds);
	}
}

function LoadTime(tempo){
	var minutes = Math.round((tempo - 30)/60);
	var remainingSeconds = tempo % 60;
	if (remainingSeconds < 10) {
		remainingSeconds = "0" + remainingSeconds;  
	}
	if (minutes < 10) {
		minutes = "0" + minutes;  
	}
	
	document.getElementById('cronometro').innerHTML = "Tempo: <span> 00:" + minutes + ":" + remainingSeconds + "</span>";
}			

function secondPassed() {
	pastSeconds++;
	seconds--;
	
	if(relogioInvertido == true)
		seconds = pastSeconds;
		
	var minutes = Math.round((seconds - 30)/60);
	var remainingSeconds = seconds % 60;
	if (remainingSeconds < 10) {
		remainingSeconds = "0" + remainingSeconds;  
	}
	if (minutes < 10) {
		minutes = "0" + minutes;  
	}
	document.getElementById('cronometro').innerHTML = "Tempo: <span> 00:" + minutes + ":" + remainingSeconds + "</span>";
	
	if(existeRegraTempo){
		if(pastSeconds == document.getElementById("txtTempoInicialTempo").value)
			regraTempoAtiva = true;
		if(pastSeconds > (parseInt(document.getElementById("txtTempoFinalTempo").value) + parseInt(document.getElementById("txtTempoInicialTempo").value)))
			regraTempoAtiva = false;
			
		if(regraTempoAtiva)
		{
			tempoRegra++;
			if(tempoRegra == document.getElementById("txtCicloRepeticao").value)
			{
				triggerEvent(document.getElementById('inputHiddemMensagem'), 'click');
				tempoRegra = 0;
			}
		}
		
	}
	
	if(existeRegraMovimento)
	{
		if(pastSeconds == document.getElementById("txtTempoInicialMovimento").value)
			regraMovimentoAtiva = true;
		if(pastSeconds > (parseInt(document.getElementById("txtTempoFinalMovimento").value) + parseInt(document.getElementById("txtTempoInicialMovimento").value))){
			regraMovimentoAtiva = false;
		}
	}
	
	
	if(pastSeconds == $("#txtMomento").val() && $("#txtMomento").val().trim() != "")
	{

		if($("#txtVisao").val().trim() == "invisivel")
			$("#cronometro").css("display","block");
		else
			$("#cronometro").css("display","none");
		
	}
	
	if ((seconds == 0 && relogioInvertido == false) || (pastSeconds == $("#txtTempo").val() && relogioInvertido == true) ) {
		clearInterval(countdownTimer);
		triggerEvent(document.getElementById('inputHiddem'), 'click');
	} else {
		
		
		
		var s = pastSeconds % 60;
		var m = Math.round((pastSeconds - 30)/60);
		
		if (s < 10) {
			s = "0" + s;  
		}
		
		if (m < 10) {
			m = "0" + m;  
		}
		
		timePast = "00:" + m + ":" + s;
	}
}

$(document).ready(function (){
	
	$("#inputHiddem").click(function(){
		EndGame();
	});
	
	$("#inputHiddemMensagem").click(function(){
	
		if($("#mensagem").dialog( "isOpen" ) === true || $("#mensagemExtra").dialog( "isOpen" ) === true)
		{
			saveExtraData("impedida");
			return;
		}
	
		if(!regraMovimentoAtiva)
		{
			pontuacaoTipo = 1;
			var textoPontos = ($("#txtPontuacaoTempo").val() == 1)? " ponto" : " pontos";
			var mensagemPontos = $("#txtMensagemPontuacao").val().replace("{PONTOS}",$("#txtPontuacaoTempo").val() + textoPontos);
			var mensagem = $("#txtPerguntaTempo").val();
		}
		else
		{
			pontuacaoTipo = 2;
			var textoPontos = ($("#txtPontuacaoMovimento").val() == 1)? " ponto" : " pontos";
			var mensagemPontos = $("#txtMensagemPontuacao").val().replace("{PONTOS}",$("#txtPontuacaoMovimento").val() + textoPontos);
			var mensagem = $("#txtPerguntaMovimento").val();
		}
			
		
		//$("#mensagemPontuacaoExtra").html(mensagemPontos);
		$("#perguntaExtra").html(mensagem);
	
		$( "#mensagemExtra" ).dialog( "open" );
		$("#ui-id-2").html(mensagemPontos);
	});
	
	//Tela de fim de jogo
	function EndGame()
	{
		clearInterval(countdownTimer);
		$("#game").css('display','none');
		$("#endGame").css('display','block');
		
		if ($("#mensagem").dialog( "isOpen" ) === true)
			$("#mensagem").dialog("close");
		else if ($("#mensagemExtra").dialog( "isOpen" ) === true)
			$("#mensagemExtra").dialog( "close" );
		
		$.ajax({
			type:'GET',
			url:'source/getEndGame.php',
			data:{jogoId:$("#txtJogo").val()},
			dataType:'json'
		}).done(function(data){
			$("#pontuacao").html(data.Pontos);
			$("#tempoCorrido").html(Math.round((pastSeconds - 30)/60)+" Minutos e "+(pastSeconds % 60)+" Segundos");
		});
		
	}
	
	$("#Iniciar").click(function(){
		$(this).parent().css("display","none");
		$("#instrucoes").css("display","");
	});
	
	LoadModelInstructions();
	
	function dropSuccess(){
		var textoPontos = ($("#txtPontos").val() == 1)? " ponto" : " pontos";
		var mensagemPontos = $("#txtMensagemPontuacao").val().replace("{PONTOS}",$("#txtPontos").val() + textoPontos);
		var mensagem = $("#txtMensagem").val();
		
		//$("#mensagemPontuacao").html(mensagemPontos);
		$("#pergunta").html(mensagem);
		$("#mensagem").dialog( "open");
		$("#ui-id-1").html(mensagemPontos);
		
		//$( "#mensagem" ).dialog( "open");
	}
	
	function AswerCorrect()
	{
		if($("#resposta").val().trim() == ""){
			$("#respostaErro").html("Porfavor, responda a pergunta!");
			return;
		}
		
		saveFaseData(true);
		
		$("#resposta").val("");
		$("#respostaErro").html("");
		
		$( "#mensagem" ).dialog( "close" );
		
		$.each($(".lado"),function(){
			if($(this).html().trim() != "")
			{
				$(this).find("img").attr('src','');
				$("#campoCarta").html($(this).html());
				$("#campoCarta").css("display","none");
				$("#campoCartaLoader").css("display","");
				$(this).html("");
			}
		});
		
		CreateFase();
	}
	
	
	function AswerExtraCorrect()
	{
		if($("#respostaExtra").val().trim() == ""){
			$("#respostaErroExtra").html("Porfavor, responda a pergunta!");
			return;
		}
		var regra = "";
		if(pontuacaoTipo == 1)
			regra = "tempo";
		else if(pontuacaoTipo == 2)
			regra = "movimento";
		
		saveExtraData(regra);
		var achou = false;
		
		$("#respostaExtra").val("");
		$("#respostaErroExtra").html("");
		
		$( "#mensagemExtra" ).dialog( "close" );
		
		$.each($(".lado"),function(){
			if($(this).html().trim() != "")
			{
				$(this).find("img").attr('src','');
				$("#campoCarta").html($(this).html());
				$("#campoCarta").css("display","none");
				$("#campoCartaLoader").css("display","");
				$(this).html("");
				achou = true;
			}
		});
		
		if(!achou)
		{
			$("#campoCarta").css("display","none");
			$("#campoCartaLoader").css("display","");
		}
		
		CreateFase();
	}
	
	
	function saveFaseData(acertou){
	
		usuario = $("#txtUsuario").val();
		model = $("#txtModelo").val();
		fase = $("#txtFase").val();
		tempoTotal = timePast;
		pontosTotal = (acertou == true) ? $("#txtPontos").val() : 0;
		cartaImg = $("#txtCarta").val();
		respostaFase = $("#resposta").val();
		jogo = $("#txtJogo").val();
	
	
		$.ajax({
			type:'POST',
			url:'source/saveFaseData.php',
			data:{usuarioId : usuario,modelId : model, faseId : fase , tempo : tempoTotal, pontos: pontosTotal, carta: cartaImg, resposta : respostaFase, jogoModelo:jogo}
		}).done(function(data){
			
		});
		
	}
	
	function saveExtraData(regra){
	
		model = $("#txtModelo").val();
		fase = $("#txtFase").val();
		
		if(regra == "tempo")
			pontosTotal = $("#txtPontuacaoTempo").val();
		else if(regra == "impedida")
			pontosTotal = 0;
		else
			pontosTotal = $("#txtPontuacaoMovimento").val();
		
		respostaFase = $("#respostaExtra").val();
		
		if(regra == "impedida")
			respostaFase = "";
		
		tempoTotal = timePast;
		jogo = $("#txtJogo").val();
		Id = $("#txtIdTempo").val();
	
	
		$.ajax({
			type:'POST',
			url:'source/saveExtraData.php',
			data:{idRegra : Id,tipoRegra : regra, resposta : respostaFase , Tempo : tempoTotal, pontos: pontosTotal, idModelo : model, idJogo:jogo}
		}).done(function(data){
			
		});
		
	}
	
	
	function LoadModelInstructions(){
		
		$.ajax({
				type:'GET',
				url:'source/getModelData.php',
				dataType:'json',
				data:{usuarioId : $("#txtUsuario").val()}
			}).done(function(retorno){
				
				$("#instrucoes").html("");
				var primeiro = 0;
				$("#txtModelo").val(retorno.ModeloId);
				$("#txtTempo").val(retorno.Tempo);
				$("#txtVisao").val(retorno.Visao);
				$("#txtMomento").val(retorno.Momento);
				$("#txtOrdem").val(retorno.Ordem);
				$("#txtJogo").val(retorno.Jogo);
				
				if(retorno.Ordem == "Crescente")
					relogioInvertido = true;
				else
					relogioInvertido = false;
					
				if(retorno.Visao == "invisivel")
					$("#cronometro").css("display","none");
				
				seconds = retorno.Tempo;
				
				var tamanhoInstrucoes = retorno.Instrucoes.length;
				
				$.each(retorno.Instrucoes,function(key,val){
					
					if((key+1) == retorno.Instrucoes.length)
					{
						$("#instrucoes").append("<div id='instrucao"+(key+1)+"' style='width:800px;display:none;'>" + 
												"<p class='labelInst'>Instruções</p>" + 
												"<div class=''>" +
													"<div class='boxInst'>" +
														"<input class='next' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"START\");' type='button' id='inst"+(key+1)+"' value='Iniciar Jogo'/>" +
														"<div class='corpoInst'><div class='numbers'>"+(key+1)+"</div><div class='separator'> de </div><div class='numbers'>"+tamanhoInstrucoes+"</div></div>" +
														"<input class='previous' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"instrucao"+(key)+"\");' type='button' id='inst"+(key+1)+"' value='Voltar'/>" +
													"</div>" +
												"</div>" +
												"<p class='instrucao'>"+val+"<p></div>");
						return;
					}
					
					if(primeiro == 0)
					{
						$("#instrucoes").append("<div id='instrucao"+(key+1)+"' style='width:800px;'>" + 
												"<p class='labelInst'>Instruções</p>" +
												"<div class=''>" +
													"<div class='boxInst'>" +
														"<input class='next' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"instrucao"+(key+2)+"\");' type='button' id='inst"+(key+1)+"' value='Próxima'/>" +
														"<div class='corpoInst'><div class='numbers'>"+(key+1)+"</div><div class='separator'> de </div><div class='numbers'>"+tamanhoInstrucoes+"</div></div>" +
														"<input class='previous' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"instrucao"+(key)+"\");' type='button' id='inst"+(key+1)+"' disabled=disabled value='Voltar'/>" +
													"</div>" +
												"</div>" +
												"<p class='instrucao'>"+val+"</p></div>");
						primeiro++;
					}else{
						$("#instrucoes").append("<div id='instrucao"+(key+1)+"' style='width:800px;display:none;'>" + 
												"<p class='labelInst'>Instruções</p>" + 
												"<div class=''>" +
													"<div class='boxInst'>" +
														"<input class='next' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"instrucao"+(key+2)+"\");' type='button' id='inst"+(key+1)+"' value='Próxima'/>" +
														"<div class='corpoInst'><div class='numbers'>"+(key+1)+"</div><div class='separator'> de </div><div class='numbers'>"+tamanhoInstrucoes+"</div></div>" +
														"<input class='previous' onClick='javascript:hideShowIntructions(\"instrucao"+(key+1)+"\",\"instrucao"+(key)+"\");' type='button' id='inst"+(key+1)+"' value='Voltar'/>" +
													"</div>" +
												"</div>" +
												"<p class='instrucao'>"+val+"</p></div>");
					}
					
				});
				
				$.each(retorno.RegrasTempo,function(key,valor){
				
					existeRegraTempo = true;
					
					$("#txtIdTempo").val(valor["idRegra"]); 
					$("#txtTempoInicialTempo").val(valor["TempoInicial"]); 
					$("#txtTempoFinalTempo").val(valor["TempoFinal"]);
					$("#txtCicloRepeticao").val(valor["CicloRepeticao"]);
					$("#txtPontuacaoTempo").val(valor["Pontuacao"]);
					$("#txtPerguntaTempo").val(valor["Pergunta"]);

					
					return false;
					
				});
				
				$.each(retorno.RegrasMovimento,function(key,valor){
					existeRegraMovimento = true;
					
					$("#txtIdMovimento").val(valor["idRegra"]); 
					$("#txtTempoInicialMovimento").val(valor["TempoInicial"]); 
					$("#txtTempoFinalMovimento").val(valor["TempoFinal"]);
					$("#txtPosicao").val(valor["Posicao"]);
					$("#txtPontuacaoMovimento").val(valor["Pontuacao"]);
					$("#txtPerguntaMovimento").val(valor["Pergunta"]);

					return false;
					
				});
				
				//TODO: Load images
				$.loadImages(retorno.Imagens,function(){
					$("#loadingGame").css("display","none");
					$("#startGame").css("display","block");
				});
				
				CreateFase();
				
			});
		
	}
	
	
	function CreateFase(){
			var contadorDeFases = $("#txtFase").val();
			contadorDeFases++;
			$("#txtFase").val(contadorDeFases);
			$(".lado").css({"box-shadow": "",opacity: 1});
			
			$.ajax({
				type:'GET',
				url:'source/getFaseData.php',
				dataType:'json',
				data:{modelId : $("#txtModelo").val(), faseId : $("#txtFase").val()}
			}).done(function(retorno){
				
				if( typeof retorno.endGame == "undefined" && retorno.endGame || retorno.endGame == 0)
				{
					EndGame();
					return;
				}
				
				var mensagem = retorno.Mensagem;
				var pontos = retorno.Pontos;
				var imagemCarta = retorno.ImagemCarta;
				var lado = retorno.Lado;
				
				$("#txtMensagem").val(mensagem);
				$("#txtPontos").val(pontos);
				$("#txtLado").val(lado);
				
				$("#txtCarta").val(imagemCarta);
				$("#carta").attr('src',"cartas/"+imagemCarta+".png");
				//CreateTime();
				$("#campoCarta").css("display","");
				$("#campoCartaLoader").css("display","none");
				
			});
			
			var cartaP = $(".card_onload_arrastar").attr("src");
			var cartaD = $(".card_onload_arrastar");
			
			$(".card_onload_arrastar").draggable({
					drag: function (event, ui){
						$(this).css({
							   border: "1px solid #999"});
					},
					revert: "invalid",
					revertDuration: 500,
					stop: function (event, ui){
						$(this).css({
						   "box-shadow": "0px 0px 10px yellow",
						   opacity: 100
						});
					}
			});  
			
			
			$(".lado").droppable({
				drop:function (event, ui){
					
					$(this).css({
						border: "1px solid #9EB4BC",
						"box-shadow": "0px 0px 10px green",
						opacity: 0.6
					});
					
					$(this).html($("#campoCarta").html());
							$("#campoCarta").html("");
								
							$("#carta").css('left','0');
							$("#carta").css('top','0');
							
							var lado = $("#txtLado").val();
							
							if(regraMovimentoAtiva)
							{
								lado = $("#txtPosicao").val();
								if(lado.trim() == "esquerda")
									lado = "esquerda";
								else if(lado.trim() == "direita")
									lado = "direita";
								else if(lado.trim() == "ambos")
									lado = "ambos";
							}
							
							if(lado == "ambos")
								lado = "ladoEsquerdo|ladoDireito";
							else if(lado == "esquerda")
								lado = "ladoEsquerdo";
							else if(lado == "direita")
								lado = "ladoDireito";
							
							if(lado.search(new RegExp($(this).attr("id"), "i")) < 0 || regraTempoAtiva == true)
							{
								$.each($(".lado"),function(){
									if($(this).html().trim() != "")
									{
										$(this).find("img").attr('src','');
										$("#campoCarta").html($(this).html());
										$("#campoCarta").html($(this).html());
										$("#campoCarta").css("display","none");
										$("#campoCartaLoader").css("display","");
										$(this).html("");
									}
								});
								
								saveFaseData(false);
								
								CreateFase();
								return;
							}
							
							if(!regraMovimentoAtiva)
								dropSuccess();
							else{
								$("#inputHiddemMensagem").click();
							}
					
				},
				
				tolerance: "intersect",
				
				over: function (event, ui){
					 $(this).css({
						   "box-shadow": "0px 0px 10px green",
						   opacity: 0.6
						});
				},
				
				out: function (event, ui){
					 $(this).css({
						   "box-shadow": "0px 0px 10px red",
						   opacity: 0.6
						});
				}
			});

			
			$( "#mensagem" ).dialog({
				autoOpen: false,
				resizable: false,
				modal: true,
				height:320,
				width:500,
				closeOnEscape: false,
				buttons: {
					Próxima: function() {
						AswerCorrect();
					}
				},
				open: function(event, ui) {
					$(this).parents(".ui-dialog:first").find(".ui-dialog-titlebar-close").remove();
				}
			});
			
			$( "#mensagemExtra" ).dialog({
				autoOpen: false,
				resizable: false,
				modal: true,
				height:320,
				width:500,
				closeOnEscape: false,
				buttons: {
					Próxima: function() {
						AswerExtraCorrect();
					}
				},
				open: function(event, ui) {
					$(this).parents(".ui-dialog:first").find(".ui-dialog-titlebar-close").remove();
				}
			});
		
	}
    
});
