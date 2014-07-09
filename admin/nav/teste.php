<?php 

	include "../Connections/config.php";

	//Verifica se a ação de submit no form foi efetuada
	if (isset($_POST['btnNewInstrucao'])) {

		//Percorre o vetor que é passado com todas informações e as grava
		for ($i=0; $i <count($_POST['ordem']) ; $i++) {

			//Verifica se todas a variáveis não estão vazias
			if(!empty($_POST['ordem'][$i]) and !empty($_POST['instrucao'][$i]) and !empty($_POST['modelo'][$i])){

				//Armazena os valores correntes no loop
				$ordem 	   = trim($_POST['ordem'][$i]);
				$instrucao = trim($_POST['instrucao'][$i]);
				$modelo    = trim($_POST['modelo'][$i]);

				//SQL para verificar se há algum registro na mesma posição e com mesmo modelo
				$sqlVerifica = "SELECT * FROM sv_iteminstrucao ".
							   "WHERE ".
							   "item_modeloId = :modelo AND ". 
							   "item_ordem = :ordem ";

				try {

					//Execução da verificação de registro de mesma posição e modelo
					$queryVerifica=$conecta->prepare($sqlVerifica);
					$queryVerifica->bindValue(":modelo", $modelo, PDO::PARAM_STR);
					$queryVerifica->bindValue(":ordem", $ordem, PDO::PARAM_STR);
					$queryVerifica->execute();

					//Armazena o valor retornado pela consulta
					$resultadoVerifica = $queryVerifica->rowCount(PDO::FETCH_ASSOC);
										   	
			    } catch (PDOException $erro) {
			   		echo "Erro ao realizar uma verificação!";
			    }

			    //Verifica se o valor retornado pela consulta se há ou não algum registro na mesma posição e modelo
			    if (isset($resultadoVerifica) && !empty($resultadoVerifica) && $resultadoVerifica != 0) {
			    	
			    	//SQL responsável por atualizar a posição do registro que está na posição desejada e todos após a ele em +1
			    	$sqlUpdate = "UPDATE sv_iteminstrucao ".
			    				 "SET sv_iteminstrucao.item_ordem = sv_iteminstrucao.item_ordem + 1 ". 
								 "WHERE sv_iteminstrucao.item_modeloId = :modeloU ".
								 "AND sv_iteminstrucao.item_ordem >= :ordemU";

					try {
						//Execução da atualização dos registros na tabela
						$queryUpdate=$conecta->prepare($sqlUpdate);						    					    	
						$queryUpdate->bindValue(":modeloU", $modelo, PDO::PARAM_STR);
						$queryUpdate->bindValue(":ordemU", $ordem, PDO::PARAM_STR);
						$queryUpdate->execute();

				    } catch (PDOException $erroD) {
				    	echo "Erro durante uma gravação não normal!";
				    }

				    //Depois de ter reorganizado os registros de acordo com a posição desejada faz a inserção no banco de dados
				    $sqlGravaAlterado = "INSERT INTO ".
			    					    "sv_iteminstrucao (item_modeloId, item_instrucaoId, item_ordem) ".
			    					    "VALUES (:modeloY, :instrucaoY, :ordemY)";	    				

				    try {
				    	//Execução da gravação dos registros no banco de dados
		    	    	$queryGravaAlterado=$conecta->prepare($sqlGravaAlterado);
						$queryGravaAlterado->bindValue(":modeloY", $modelo, PDO::PARAM_STR);
						$queryGravaAlterado->bindValue(":instrucaoY", $instrucao, PDO::PARAM_STR);
						$queryGravaAlterado->bindValue(":ordemY", $ordem, PDO::PARAM_STR);
						$queryGravaAlterado->execute();

		    	    } catch (PDOException $e) {
		    	    	echo "Erro ao gravar alterado";
		    	    }	    

			    }else{

			    	//Caso não haja nenhum registro na mesma posição que o informado e 
			    	//com o mesmo modelo simplesmente é inserido no banco de dados
			    	$sqlGravaNormal = "INSERT INTO ".
			    					  "sv_iteminstrucao (item_modeloId, item_instrucaoId, item_ordem) ".
			    					  "VALUES (:modeloG, :instrucaoG, :ordemG)";

			    	try {
			    		//Executando a gravação normal
			    		$queryGravaNormal = $conecta->prepare($sqlGravaNormal);
			    		$queryGravaNormal->bindValue(":modeloG", $modelo, PDO::PARAM_STR);
			    		$queryGravaNormal->bindValue(":instrucaoG", $instrucao, PDO::PARAM_STR);
			    		$queryGravaNormal->bindValue(":ordemG", $ordem, PDO::PARAM_STR);
			    		$queryGravaNormal->execute();

			    	} catch (PDOException $erroGravaNormal) {
			    		echo "Erro ao gravar normalmente!";
			    	}

			    }

			}	
			
		}

	}
?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>Teste</title>
	<style>
		*{padding: 0; margin: 0;}
		form{display: block;}
		fieldset{padding: 10px; display: block;}
	</style>
</head>
<body>
	<form method="post" action="">
		<fieldset>
			
			<label for="">Ordem:</label>
			<input name="ordem[0]" type="text">
			<label for="">Instrução ID:</label>
			<input name="instrucao[0]" type="text">
			<label for="">Modelo:</label>
			<input name="modelo[0]" type="text">

		</fieldset>

		<br/>

		<fieldset>
			
			<label for="">Ordem:</label>
			<input name="ordem[1]" type="text">
			<label for="">Instrução ID:</label>
			<input name="instrucao[1]" type="text">
			<label for="">Modelo:</label>
			<input name="modelo[1]" type="text">

		</fieldset>
		
		<br/>
		<br/>
		
		<input name="btnNewInstrucao" value="Gravar" type="submit">

		<br/>
		<br/>
	</form>

<?php 



$sql = "SELECT ".
		   "sv_iteminstrucao.item_id as id, ". 
		   "sv_iteminstrucao.item_ordem as ordem, ".
		   "sv_modelos.modelNome as modelo, ".
		   "sv_instrucoes.instrucaoTxt as instrucao ".
	   "FROM sv_iteminstrucao ".
   	   "INNER JOIN sv_modelos ON sv_iteminstrucao.item_modeloId = sv_modelos.modelId ".
   	   "INNER JOIN sv_instrucoes ON sv_iteminstrucao.item_instrucaoId = sv_instrucoes.instrucaoId ".
   	   "WHERE sv_iteminstrucao.item_modeloId = 1 ORDER BY ordem";

try {

	$query = $conecta->prepare($sql);
	$query->execute();

	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $erro) {
	echo "Erro ao selecionar dados do BD!".$erro->getMessage();
}

	foreach ($resultado as $valores) {
		echo "ID: ".$valores['id']."<br/>";
		echo "MODE: ".$valores['modelo']."<br/>";
		echo "INST: ".$valores['instrucao']."<br/>";
		echo "ORDER: ".$valores['ordem']."<br/>";
		echo "<hr />";
	}
?>
	
</body>
</html>