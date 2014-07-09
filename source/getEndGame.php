<?php

	if(!isset($_GET) || !isset($_GET["jogoId"]) )
		return;
		
	$jogoId = $_GET["jogoId"];
	
	include_once '../Connections/config.php';

	//Pontos da Fase
	$sql_gamePainel = " SELECT 
							SUM(rfase.rFasePontoGanho) as pontos
						FROM 
							sv_jogo AS jogo
							LEFT JOIN sv_relatoriofase AS rfase ON rfase.JogoId = jogo.JogoId
						WHERE 
							jogo.JogoId = ".$jogoId;
	
	
	try {

        $query_gamePainel = $conecta->prepare($sql_gamePainel);                
        $query_gamePainel ->execute();

        $result_gamePainel = $query_gamePainel->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar informações do modelo</div> ";
    }
	
	$pontosFase = "";
	
	foreach ($result_gamePainel as $res){
		$pontosFase = $res["pontos"];
	}
	
	//Pontos Extras
	$sql_pontosExtras = " SELECT 
							SUM(rextra.pontosGanhos) as pontos
						FROM 
							sv_jogo AS jogo
							LEFT JOIN sv_relatorioextra AS rextra ON rextra.idJogo = jogo.JogoId
						WHERE 
							jogo.JogoId = ".$jogoId;
	
	
	try {

        $query_pontosExtras = $conecta->prepare($sql_pontosExtras);                
        $query_pontosExtras ->execute();

        $result_pontosExtras = $query_pontosExtras->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar informações do modelo</div> ";
    }
	
	$pontosExtra = "";
	
	foreach ($result_pontosExtras as $ressult){
		$pontosExtra = $ressult["pontos"];
	}
	
	$pontos = $pontosFase + $pontosExtra;
	
	$dados = Array('Pontos' => $pontos);

	echo json_encode($dados);

?>