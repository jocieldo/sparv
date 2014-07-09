<?php 

    include_once "system/restrito_game.php";
            
    include_once 'Connections/config.php';

    $mailUser = $_SESSION['MM_Username'];

    $sql_gamePainel = "SELECT * FROM sv_users WHERE userEmail = :mailUser";

    $sql_Status = "UPDATE sv_users SET userStatus = :userStatus, userNivel = :userNivel WHERE userEmail = :userEmail";

    try {

    	//Selecionando user
        $query_gamePainel = $conecta->prepare($sql_gamePainel);                
        $query_gamePainel ->bindValue(":mailUser", $mailUser, PDO::PARAM_STR);
        $query_gamePainel ->execute();

        //Resultado da seleção
        $result_gamePainel = $query_gamePainel->fetchAll(PDO::FETCH_ASSOC);

        //Ataulizando campo status da tabela users segundo a SQL acima (sql_Status)
        $query_Status=$conecta->prepare($sql_Status);
        $query_Status->bindValue(":userStatus", "Jogou", PDO::PARAM_STR);
        $query_Status->bindValue(":userEmail", $mailUser, PDO::PARAM_STR);
        $query_Status->bindValue(":userNivel", "nao", PDO::PARAM_STR);
        $query_Status->execute();

    } catch (PDOException $erro) {
        echo "<div class='alertaN'>Erro ao selecionar usuario</div> ";
    }

    foreach ($result_gamePainel as $res){

        $userID = $res['userID'];
        $userNome= $res['userNome'];
        $userSexo= $res['userSexo'];
        $userDataNascimento= $res['userDataNascimento'];
        $userEmail= $res['userEmail'];
        $userNivel= $res['userNivel'];
        $userStatus= $res['userStatus'];              
       	$userModelId = $res['userModelId']; 

       	//Faz a verificação de permissão de acesso. Caso seja verdadeiro continua caso contrário redireciona para logout

       	if($userNivel == 'nao'){
       		header('location: logout.php');
       		exit;
       	} 

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>
		<link href="styles/jquery-ui-1.10.3.custom.css" type="text/css" rel="stylesheet" />
        <link href="game_style.css" type="text/css" rel="stylesheet" />
        <title>SPARV | Game -  <?php echo $userNome;?> </title>
    </head>
    <body>        
        <div id="box">
                <div id="painel_game">
                    
                    <div class="content_cartas">
                        
                        <div id="header_game">
                            
                            <div class="user_info">
                                
                                <!-- Saudacoes com if para bem vindo ou bem dia -->
                                Bem vind<?php if($userSexo == "M"){echo "o";}elseif ($userSexo == "F"){echo "a";} ?>, 
                                <span><?php echo $userNome= $res['userNome']; ?></span> | <a href="logout.php?usr=<?php echo $userID; ?>&md=<?php echo $userModelId;?>">Cancelar e sair</a>
                                
                            </div><!-- user_info -->
                            
                            <div class="cronometro" id="cronometro">
                                
                                Tempo: <span>00:00:00</span>
                                
                            </div><!-- cronometro -->
                            
                        </div><!-- header_game -->
						
						
				<!-- TELA CARREGANDO -->
			    <div id="loadingGame" style="text-align:center;">
				    <h1 style="color:green;font-weight:bold;font-size:60px;margin-top:200px;">Carregando
						<img class="" src="images/ajax-loader.gif" alt="" style="width:50px;height:50px;margin-top:20px;" title=""/>
					</h1>
			    </div>
				
				
			    <!-- TELA INICIAL -->
			    <div id="startGame" style="text-align:center;display:none;">
				    <h1 style="color:black;font-weight:bold;font-size:60px;margin-top:150px;">SPARV Game</h1>
				    <input type="button" style="margin-top:80px;" class="botao" id="Iniciar" value="Iniciar"/>
			    </div>
    
			    <!-- TELA DE INSTRUÇÕES -->
			    <div id="instrucoes" style="display:none;">
				    
			    </div>
			    
			    <div id="game" style="display:none;">
			    
				    <div class="space_left lado" id="ladoEsquerdo">
					    
				    </div><!-- space left-->
				    
				    <div class="space_right lado" id="ladoDireito">
					    
				    </div><!-- space_right -->
				    
				    <div class="card_onload" id="campoCarta">
					    
					    <img id="carta"  class="card_onload_arrastar" src="" alt="" title="" />
					    
				    </div><!-- card_onload -->
				    
				    <div class="card_onload" id="campoCartaLoader" style="display:none;"align="center">
					    
					    <img class="" src="images/ajax-loader.gif" alt="" style="width:50px;height:50px;margin-top:70px;" title=""/>
					    
				    </div><!-- card_onload -->
				    
			    </div>
			    
			    <!-- TELA DO FIM DO JOGO -->
			    <div id="endGame" style="display:none;text-align:center;">
				    <h1 style="color:red;font-weight:bold;font-size:60px;margin-top:100px;">Fim de Jogo</h1>
				    <h3>Você ganhou:</h3>
				    <h1 id="pontuacao" style="color:green;font-weight:bold;font-size:50px;"></h1>
				    <h3>Pontos</h3>
				    <h3>Em</h3>
				    <h1 id="tempoCorrido" style="color:green;font-weight:bold;font-size:50px;"></h1>
				    <div style="margin-top:30px;"><h2>Obrigado pela participação!!!</h2></div>
			    </div>
			    
                    </div><!-- Content_cartas -->
                </div><!-- painel_game -->
                
                <!-- TELA DE MENSAGEM -->
		<div style="display:none;" id="mensagem" title="Responda a Pergunta - SPARV">
		    <br><p id="pergunta"></p>
		    <br><p id="respostaErro" style="color:red;"></p>
		    <div align="center">
			<textarea id="resposta" cols="55" rows="4"></textarea>
		    </div>
		</div>
		
		<!-- TELA DE MENSAGEM EXTRA-->
		<div style="display:none;" id="mensagemExtra" title="Responda a Pergunta - SPARV">
		    <br><p id="perguntaExtra"></p>
		    <br><p id="respostaErroExtra" style="color:red;"></p>
		    <div align="center">
			<textarea id="respostaExtra" cols="55" rows="4"></textarea>
		    </div>
		</div>
				
		<!-- INICIO - PARAMETROS DO SERVODOR -->

		<input type="hidden" value="" id="txtModelo" />
		<input type="hidden" value="0" id="txtFase" />
		<input type="hidden" value="<?php echo $userID; ?>" id="txtUsuario" />
		<input type="hidden" value="" id="txtMensagem" />
		<input type="hidden" value="" id="txtPontos" />
		<input type="hidden" value="" id="txtTempo" />
		<input type="hidden" value="" id="txtLado" />
		<input type="hidden" value="" id="txtJogo" />
		<input type="hidden" value="" id="txtVisao" />
		<input type="hidden" value="" id="txtMomento" />
		<input type="hidden" value="" id="txtOrdem" />
		<input type="hidden" value="" id="txtCarta" />
		<input type="hidden" value="" id="inputHiddem" />
		<input type="hidden" value="" id="inputHiddemMensagem" />
		<input type="hidden" value="Você ganhou {PONTOS}!" id="txtMensagemPontuacao" />
		
		<!-- REGRAS DE TEMPO -->
		<input type="hidden" value="" id="txtIdTempo" />
		<input type="hidden" value="" id="txtTempoInicialTempo" />
		<input type="hidden" value="" id="txtTempoFinalTempo" />
		<input type="hidden" value="" id="txtCicloRepeticao" />
		<input type="hidden" value="" id="txtPontuacaoTempo" />
		<input type="hidden" value="" id="txtPerguntaTempo" />
		
		<!-- REGRAS DE MOVIMENTO -->
		<input type="hidden" value="" id="txtIdMovimento" />
		<input type="hidden" value="" id="txtTempoInicialMovimento" />
		<input type="hidden" value="" id="txtTempoFinalMovimento" />
		<input type="hidden" value="" id="txtPosicao" />
		<input type="hidden" value="" id="txtPontuacaoMovimento" />
		<input type="hidden" value="" id="txtPerguntaMovimento" />
		
		<!-- FIM - PARAMETROS DO SERVODOR -->
				
        <?php
            }
        ?>
        </div><!-- Box -->
        <div id="footer">
                <p>Copyright © 2013 UFC / LEAC. All rights reserved. Developed by InforTech Tecnologias.</p>
        </div><!-- footer -->
		
	<script type="text/javascript" src="js/jquery-2.0.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/game.js"></script>
	<script type="text/javascript" src="js/jquery.loadImages.1.1.0.js"></script>
    </body>
</html>
