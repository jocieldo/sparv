<?php
	
	date_default_timezone_set("America/Fortaleza");
	
	if(!isset($_GET) || !isset($_GET["usuarioId"]) )
		return;
		
	$usuarioId = $_GET["usuarioId"];
	
	include_once '../Connections/config.php';
	
	
	//Seleciona os dados do modelo
	$sql_gamePainel = " SELECT 
						mode.modelId, inst.instrucaoTxt, temp.tempoTotal, temp.tempoVisao,temp.tempoMomento, temp.tempoOrdem
					FROM 
						sv_modelos AS mode 
						LEFT JOIN sv_users AS user ON user.userModelId = mode.modelId
						LEFT JOIN sv_iteminstrucao AS iti ON iti.item_modeloId = mode.modelId
						LEFT JOIN sv_instrucoes AS inst ON inst.instrucaoId = iti.item_instrucaoId
						LEFT JOIN sv_tempo AS temp ON temp.tempoId = mode.modelTempoId
					WHERE 
						user.userID = ".$usuarioId." ORDER BY iti.item_ordem ASC ";
	
	
	try {

        $query_gamePainel = $conecta->prepare($sql_gamePainel);                
        $query_gamePainel ->execute();

        $result_gamePainel = $query_gamePainel->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar informações do modelo</div> ".$erro->getMessage();
    }
	
	$modeloId = "";
	$tempo = "";
	$visao = "";
	$momento = "";
	$ordem = "";
	$intrucoes = "";
	
	foreach ($result_gamePainel as $res){
		$modeloId = $res["modelId"];
		$tempo = $res["tempoTotal"];
		$visao = $res["tempoVisao"];
		$momento = $res["tempoMomento"];
		$ordem = $res["tempoOrdem"];
		$instrucoes[] = $res["instrucaoTxt"];
	}
	
	
	//Criar o jogo para o usuario
	try{

		$timestamp = date("dmYHis").$usuarioId;    
		
		$sql_criaJogo = "INSERT INTO sv_jogo (UsuarioId, Data, Identificador, Modelo) values (:UsuarioId, :Data, :Identificador, :Modelo)";
		$query_criaJogo = $conecta->prepare($sql_criaJogo);
		$query_criaJogo->bindValue(":UsuarioId", $usuarioId, PDO::PARAM_STR);
		$query_criaJogo->bindValue(":Data", date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$query_criaJogo->bindValue(":Identificador", $timestamp, PDO::PARAM_STR);
		$query_criaJogo->bindValue(":Modelo", $modeloId, PDO::PARAM_STR);
		$query_criaJogo->execute();

	}catch(PDOException $erro_criarJogo){
		echo "Erro ao criar jogo: ".$erro_criarJogo;
	}
	
	
	//Seleciona os dados do jogo
	try {
		$sql_selectJogo = "SELECT * FROM sv_jogo WHERE Identificador = '".$timestamp."'";
        $query_selectJogo = $conecta->prepare($sql_selectJogo);                
        $query_selectJogo ->execute();

        $result_game = $query_selectJogo->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar informações do modelo</div> ".$erro;
    }
	
	$jogo = "";
	
	foreach ($result_game as $result){
		$jogo = $result["JogoId"];
	}
	
	
	//Seleciona as regras extras de tempo
	try {
		$sql_selectRegraTempo = "SELECT 
								rtempo.idRegra,rtempo.tempoInicial,rtempo.tempoFinal,rtempo.cicloRepeticao,rtempo.pontuacao,perg.perguntaTxt
							FROM 
								sv_regrastempo as rtempo
								left join sv_perguntas as perg on perg.perguntaId = rtempo.idPergunta
							WHERE idModelo = '".$modeloId."'";
					
        $query_selectRegraTempo = $conecta->prepare($sql_selectRegraTempo);                
        $query_selectRegraTempo ->execute();

        $result_RegraTempo = $query_selectRegraTempo->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar as regras de tempo</div> ".$erro;
    }
	
	$regrasTempo = "";
	
	foreach ($result_RegraTempo as $resultTempo){
		$regrasTempo[] = Array("TempoInicial" => $resultTempo["tempoInicial"],
		"idRegra" => $resultTempo["idRegra"],
		"TempoFinal" => $resultTempo["tempoFinal"],
		"CicloRepeticao" => $resultTempo["cicloRepeticao"],
		"Pontuacao" => $resultTempo["pontuacao"],
		"Pergunta" => $resultTempo["perguntaTxt"]);
	}
	
	//Seleciona as regras extras de movimento
	try {
		$sql_selectRegraMovimento = "SELECT 
										rmovimento.idRegra,rmovimento.tempoInicial,rmovimento.tempoFinal,rmovimento.posicao,rmovimento.pontuacao,perg.perguntaTxt
									FROM 
										sv_regrasmovimento as rmovimento
										left join sv_perguntas as perg on perg.perguntaId = rmovimento.idPergunta
									WHERE idModelo = '".$modeloId."'";
					
        $query_selectRegraMovimento = $conecta->prepare($sql_selectRegraMovimento);                
        $query_selectRegraMovimento ->execute();

        $result_RegraMovimento = $query_selectRegraMovimento->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar as regras de tempo</div> ".$erro;
    }
	
	$regraMovimento = "";
	
	foreach ($result_RegraMovimento as $resultMovimento){
		$regraMovimento[] = Array("TempoInicial" => $resultMovimento["tempoInicial"],
		"idRegra" => $resultMovimento["idRegra"],
		"TempoFinal" => $resultMovimento["tempoFinal"],
		"Posicao" => $resultMovimento["posicao"],
		"Pontuacao" => $resultMovimento["pontuacao"],
		"Pergunta" => $resultMovimento["perguntaTxt"]);
	}
	
	//SELECIONA AS IMAGENS DAS CARTAS DAS FASES DO MODELO
	try{
		$sql_selectImagens = "SELECT ca.cartaDesc FROM sv_users us
								LEFT JOIN sv_fases fa on fa.modelId = us.userModelId
								LEFT JOIN sv_cartas ca on ca.cartaId = fa.faseCartaId
								WHERE us.userID = ".$usuarioId;
		$query_selectImagens = $conecta->prepare($sql_selectImagens);
		$query_selectImagens->execute();
		
		$resultado_selectImagens = $query_selectImagens->fetchAll(PDO::FETCH_ASSOC);
		
		$imagens = "";
		
		foreach ($resultado_selectImagens as $resultImagens){
				$imagens[] = "cartas/".$resultImagens["cartaDesc"].".png";
		}
		
	}
	catch(PDOException $erro){
		echo "<div class=\"no\">Erro ao selecionar as imagens do modelo</div> ".$erro;
	}
	
	
	
	//Trata o retorno dos dados
	$dadosModelo = Array('ModeloId' => $modeloId,'Tempo' => $tempo,'Visao' => $visao,'Momento' => $momento,'Ordem' => $ordem,'Jogo' => $jogo, 'Instrucoes' => $instrucoes,
						 'RegrasTempo' => $regrasTempo, 'RegrasMovimento' => $regraMovimento, 'Imagens' => $imagens);

	echo json_encode($dadosModelo);

?>