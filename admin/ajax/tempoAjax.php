<?php
	
	include_once "../system/config.php";
	include_once "../system/restrito.php";

	$idTime = $_GET['idTime'];

	$sql = "SELECT * FROM sv_tempo WHERE tempoId = :tempoId";

	try{

		$query = $conecta->prepare($sql);
		$query->bindValue(":tempoId", $idTime, PDO::PARAM_STR);
		$query->execute();

		$quantRegistros = $query->rowCount(PDO::FETCH_ASSOC);

		if ($quantRegistros == 0) {

			echo "1";

		}

		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		
	}catch(PDOException $erro){
		echo "3";		
	}



	foreach ($resultado as $values) {

		if($quantRegistros > 0){

		 	print_r(json_encode($values));

		}

	}

?>

