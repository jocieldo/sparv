<?php 

include_once 'system/restrito.php';

	if(isset($_GET['modelo'], $_GET['ordem'])){

		include_once '../Connections/config.php';

		$modeloId = $_GET['modelo'];
		$orderId  = $_GET['ordem'];

		$sql = "DELETE FROM sv_iteminstrucao WHERE item_modeloId = :item_modeloId AND item_ordem = :item_ordem";

		try{
			$query = $conecta->prepare($sql);
			$query->bindValue(":item_ordem", $orderId, PDO::PARAM_STR);
			$query->bindValue(":item_modeloId", $modeloId, PDO::PARAM_STR);
			$query->execute();

			$endereco = "painel.php?spv=nav/instrucoesAssoc&modelo=$modeloId";

			header("location: $endereco");
			exit;

		}catch(PDOException $erro){
			echo "<h1>Erro ao excluir instrução!</h1>";
		}
	}
?>