<?php

	if(!isset($_GET) || !isset($_GET["modelId"]) )
		return;
		
	$modeloId = $_GET["modelId"];
	$faseId = $_GET["faseId"];
	
	include_once '../Connections/config.php';

	$sql_gamePainel = " SELECT 
							fase.faseId, msg.perguntaTxt, fase.fasePonto, img.cartaDesc, fase.fasePGMove
						FROM 
							sv_fases AS fase 
							LEFT JOIN sv_perguntas AS msg ON msg.perguntaId = fase.fasePerguntaId
							LEFT JOIN sv_cartas AS img ON img.cartaId = fase.faseCartaId
						WHERE 
							fase.modelId = ".$modeloId." ORDER BY fasePosicao ASC LIMIT ".($faseId-1).", 1 ";
	
	
	try {

        $query_gamePainel = $conecta->prepare($sql_gamePainel);                
        $query_gamePainel ->execute();

        $result_gamePainel = $query_gamePainel->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $erro) {
        echo "<div class=\"no\">Erro ao selecionar informações do modelo</div> ";
    }
	
	$mensagem = "";
	$pontos = "";
	$carta = "";
	$lado = "";
	
	if(count($result_gamePainel) < 1)
	{
		echo json_encode(Array('endGame' => 0));
		return;
	}
	
	foreach ($result_gamePainel as $res){
		$mensagem = $res["perguntaTxt"];
		$pontos = $res["fasePonto"];
		$carta = $res["cartaDesc"];
		$lado = $res["fasePGMove"];
	}
	
	$dados = Array('Mensagem' => $mensagem,'Pontos' => $pontos, 'ImagemCarta' => $carta,'Lado' => $lado);

	echo json_encode($dados);

?>