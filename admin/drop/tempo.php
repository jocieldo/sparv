<?php 

include_once "../Connections/config.php";

	if(isset($_GET['dp']) and !empty($_GET['dp'])){

		$timeDrop = $_GET['dp'];

		$sqlDropTime = "DELETE FROM sv_tempo WHERE tempoId = :tempoId";

		try{

			$queryDropTime = $conecta->prepare($sqlDropTime);
			$queryDropTime->bindValue(":tempoId", $timeDrop, PDO::PARAM_STR);
			$queryDropTime->execute();

			header("Location: painel.php?spv=nav/tempo");
			exit;

		}catch(PDOException $erroDropTime){
			echo "Erro ao excluir regra de tempo";

		}

	}else{
		header("Location: painel.php?spv=nav/tempo");
		exit;
	}
?>