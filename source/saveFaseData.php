<?php

	if(!isset($_POST) || !isset($_POST["usuarioId"]))
		return;
		
	include_once '../Connections/config.php';
		
	$usuarioId = $_POST["usuarioId"];
	$modelId = $_POST["modelId"];
	$faseId = $_POST["faseId"];
	$tempo = $_POST["tempo"];
	$pontos = $_POST["pontos"];
	$carta = $_POST["carta"];
	$resposta = $_POST["resposta"];
	$jogoId = $_POST["jogoModelo"];
	
	try{

		$sql_criaInstrucao = "INSERT INTO sv_relatoriofase values (0,'".$usuarioId."','".$modelId."','".$faseId."','".$jogoId."','".$resposta."','".$tempo."','".$pontos."','".$carta."')";
		$query_criaInstrucao = $conecta->prepare($sql_criaInstrucao);
		$query_criaInstrucao->execute();

	} catch(PDOException $erro_criarPergunta){
		echo "Erro ao criar pergunta: ".$erro_criarPergunta;
	}

?>