<?php 

include_once 'system/restrito.php';


if(isset($_POST['btnCriaFase'])){

    include_once '../Connections/config.php';

    $gravaDescricao = trim($_POST['faseDescricao']);
    $gravaCarta     = trim($_POST['faseCarta2']);
    $gravaCriterio  = trim($_POST['criterio']);
    $gravaPonto     = trim($_POST['fasePontos']);
    $gravaPergunta  = trim($_POST['fasePerguntaNumber']);
    $gravaModelo    = trim($_POST['faseModelo']);
    $gravaPosicao   = trim($_POST['fasePosicao']);

    $sql = "INSERT INTO sv_fases (faseCartaId, fasePerguntaId, modelId, faseDescricao, fasePonto, fasePGMove, fasePosicao) ".
                         "VALUES (:faseCartaId, :fasePerguntaId, :modelId, :faseDescricao, :fasePonto, :fasePGMove, :fasePosicao)";

    try{
        $query = $conecta->prepare($sql);
        $query->bindValue(":faseCartaId", $gravaCarta, PDO::PARAM_STR);
        $query->bindValue(":fasePerguntaId", $gravaPergunta, PDO::PARAM_STR);
        $query->bindValue(":modelId", $gravaModelo, PDO::PARAM_STR);
        $query->bindValue(":faseDescricao", $gravaDescricao, PDO::PARAM_STR);
        $query->bindValue(":fasePonto", $gravaPonto, PDO::PARAM_STR);
        $query->bindValue(":fasePGMove", $gravaCriterio, PDO::PARAM_STR);
        $query->bindValue(":fasePosicao", $gravaPosicao, PDO::PARAM_STR);
        $query->execute();

        echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/fases'>";
        exit;

    }catch(PDOException $erroGravar){

        echo "<h1>Erro ao gravar fase</h1>";

    }
}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/fases'>";
    exit;
}

?>