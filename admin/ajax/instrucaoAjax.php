<?php
	
	include_once "../system/config.php";

	$idInstrucao = $_POST['idInstrucao'];

	$sql = "SELECT * FROM sv_instrucoes WHERE instrucaoId = :intrucaoId";

	try{

		$query = $conecta->prepare($sql);
		$query->bindValue(":intrucaoId", $idInstrucao, PDO::PARAM_STR);
		$query->execute();

		$quantRegistros = $query->rowCount(PDO::FETCH_ASSOC);

		if ($quantRegistros == 0) {

			echo "1";

		}

		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		
	}catch(PDOException $erro){
		echo "Erro ao consultar instrução!";		
	}

	foreach ($resultado as $values) {

		if($quantRegistros > 0){

		 	echo $values['instrucaoTxt'];

		}

	}

?>

