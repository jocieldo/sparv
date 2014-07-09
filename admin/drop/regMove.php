<?php 
	
include_once 'system/restrito.php';

	if(isset($_GET['regra'])){

		include_once '../Connections/config.php';

		$idRegra = $_GET['regra'];
		$idRegra = (int)$idRegra;

		$sql = "DELETE FROM sv_regrasmovimento WHERE IdRegra = :IdRegra";

		try{

			$query = $conecta->prepare($sql);
			$query->bindValue(":IdRegra", $idRegra, PDO::PARAM_STR);
			$query->execute();

			header("location: painel.php?spv=nav/regMove");
			exit;

		}catch(PDOException $erro){
			header("location: painel.php?spv=nav/regMove");
			exit;
		}
	}else{
		header("location: painel.php?spv=nav/regMove");
		exit;
	}

?>