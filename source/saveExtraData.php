<?php

	if(!isset($_POST) || !isset($_POST["idRegra"]))
		return;
		
	include_once '../Connections/config.php';
		
	$idRegra = $_POST["idRegra"];
	$tipoRegra = $_POST["tipoRegra"];
	$resposta = $_POST["resposta"];
	$pontos = $_POST["pontos"];
	$tempo = $_POST["Tempo"];
	$idModelo = $_POST["idModelo"];
	$idJogo = $_POST["idJogo"];
	
	try{

		$sql_criaInstrucao = "INSERT INTO sv_relatorioextra (ID,idRegra,tipoRegra,resposta,extraTempoGasto,pontosGanhos,idModelo,idJogo) values (0,'".$idRegra."','".$tipoRegra."','".$resposta."','".$tempo."','".$pontos."','".$idModelo."','".$idJogo."')";
		$query_criaInstrucao = $conecta->prepare($sql_criaInstrucao);
		$query_criaInstrucao->execute();

	} catch(PDOException $erro_criarPergunta){
		echo "Erro ao criar pergunta: ".$erro_criarPergunta;
	}

?>