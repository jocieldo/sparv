<?php 

    include_once "system/config.php";
    
    if(isset($_GET['id']) && !empty($_GET['id'])){
        
        
        $id = (int)$_GET['id'];
        
        try {
            
            $sql_excluiPergunta = "DELETE FROM sv_users WHERE userID = :userID";
            
            $query_excluiInstrucao = $conecta->prepare($sql_excluiPergunta);
            $query_excluiInstrucao->bindValue(":userID", $id, PDO::PARAM_STR);
            $query_excluiInstrucao->execute();
            
            header("location: painel.php?spv=nav/usuarios");
            
        } catch (PDOException $erro_excluir) {
            echo $erro_excluir->getTraceAsString();
        }

        
    }  else {    
        header("location: painel.php?spv=nav/usuarios");
        exit;
    }
?>