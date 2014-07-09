<?php 

include_once 'system/restrito.php';

    if(isset($_POST['btnCriaFase2'])){

        include_once '../Connections/config.php';

        $gravaDescricao = $_POST['faseDescricao'];
        $gravaCarta     = $_POST['faseCarta2'];
        $gravaCriterio  = $_POST['criterio'];
        $gravaPonto     = $_POST['fasePontos'];
        $gravaPergunta  = $_POST['fasePerguntaNumber'];
        $gravaModelo    = $_POST['faseModelo'];
        $gravaPosicao   = $_POST['fasePosicao'];

        $sqlAltera = "UPDATE sv_fases SET ".
                                            "faseCartaId    = :faseCartaId, ".
                                            "fasePerguntaId = :fasePerguntaId, ".
                                            "modelId        = :modelId, ".
                                            "faseDescricao  = :faseDescricao, ".
                                            "fasePonto      = :fasePonto, ".
                                            "fasePGMove     = :fasePGMove, ". 
                                            "fasePosicao    = :fasePosicao ".
                     "WHERE sv_fases.faseId = :faseId";
        
            try{

                $queryAltera =  $conecta->prepare($sqlAltera);
                $queryAltera->bindValue(":faseCartaId", $gravaCarta, PDO::PARAM_STR);
                $queryAltera->bindValue(":fasePerguntaId", $gravaPergunta, PDO::PARAM_STR);
                $queryAltera->bindValue(":modelId", $gravaModelo, PDO::PARAM_STR);
                $queryAltera->bindValue(":faseDescricao", $gravaDescricao, PDO::PARAM_STR);
                $queryAltera->bindValue(":fasePonto", $gravaPonto, PDO::PARAM_STR);
                $queryAltera->bindValue(":fasePGMove", $gravaCriterio, PDO::PARAM_STR);
                $queryAltera->bindValue(":fasePosicao", $gravaPosicao, PDO::PARAM_STR);
                $queryAltera->bindValue(":faseId", $_POST['faseIdetificacao'], PDO::PARAM_STR);
                $queryAltera->execute();

                header("location: painel.php?spv=nav/fases");
                exit;

            }catch(PDOException $erroAltera){
                echo "Erro ao alterar";
            }

    }else{
        header("location: painel.php?spv=nav/fases");
        exit;
    }

?>