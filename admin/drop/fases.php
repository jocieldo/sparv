<?php 

include_once 'system/restrito.php'; 
	
if(isset($_GET['fase']) and !empty($_GET['fase'])){

	include_once '../Connections/config.php';

	$sql = "DELETE FROM sv_fases WHERE faseId = :faseId";

	try{

		$query =  $conecta->prepare($sql);
		$query->bindValue(":faseId", $_GET['fase'], PDO::PARAM_STR);
		$query->execute();

		header("location: painel.php?spv=nav/fases");
		exit;

	}catch(PDOException $erroDelete){
		echo "<h1>Erro ao deletar fase;</h1>";
	}

}
?>