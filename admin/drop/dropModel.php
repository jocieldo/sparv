<?php 

include_once 'system/restrito.php'; 

    if (isset($_GET['modelo']) && !empty($_GET['modelo'])) {

        include_once '../Connections/config.php';
        
        $sql = "DELETE FROM sv_modelos WHERE modelId = :modelId";

        try {

            $query = $conecta->prepare($sql);
            $query->bindValue(":modelId", $_GET['modelo'], PDO::PARAM_STR);
            $query->execute();

            header("location: painel.php?spv=nav/modelos");

        } catch (PDOException $e) {
            echo "erro ao excluir modelo".$e->getMessage();
        }
    }else{
        header("location: painel.php?spv=nav/dropModel");
    }

 ?>