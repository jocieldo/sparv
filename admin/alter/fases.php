<?php 

include_once 'system/restrito.php';

if(isset($_GET['fase'])){

include_once 'header.php';
include_once '../Connections/config.php';

    $sql = "SELECT ".
                "sv_fases.faseId as id, ".
                "sv_fases.faseDescricao as descricao, ".
                "sv_fases.fasePonto as pontos, ".
                "sv_fases.fasePGMove as mover, ".
                "sv_fases.fasePosicao as posicao, ".
                "sv_modelos.modelNome as modelo, ".
                "sv_modelos.modelId as modeloId, ".
                "sv_perguntas.perguntaId as perguntaId, ".
                "sv_perguntas.perguntaTxt as pergunta, ".
                "sv_cartas.cartaDirectory as carta, ".
                "sv_cartas.cartaID as cartaId, ".
                "sv_cartas.cartaDesc as cartaDesc ".
            "FROM ".
                "sv_fases ".
            "INNER JOIN sv_modelos ON sv_fases.modelId = sv_modelos.modelId ".
            "INNER JOIN sv_perguntas ON sv_fases.fasePerguntaId = sv_perguntas.perguntaId ".
            "INNER JOIN sv_cartas ON sv_fases.faseCartaId = sv_cartas.cartaID ".
            "WHERE sv_fases.faseId = :faseId";

    try{

        $query = $conecta->prepare($sql);
        $query->bindValue(":faseId", $_GET['fase'], PDO::PARAM_STR);
        $query->execute();

        $row = $query->rowCount(PDO::FETCH_ASSOC);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $values) {
            
            $fase      = $values['id'];
            $descricao = $values['descricao'];
            $pontos    = $values['pontos'];
            $mover     = $values['mover'];
            $posicao   = $values['posicao'];
            $modelo    = $values['modelo'];
            $modeloId    = $values['modeloId'];
            $pergunta  = $values['pergunta'];
            $perguntaId= $values['perguntaId'];
            $carta     = $values['carta'];
            $cartaId     = $values['cartaId'];
            $cartaDesc = $values['cartaDesc'];

        }

    }catch(PDOException $erroSelecionaFase){
        echo "Erro ao selecionar fase".$erroSelecionaFase->getMessage();
    }


?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/fases'>Fases</a> &gt; Alterar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagfases">
        <?php 

            $sqlModel = "SELECT * FROM sv_modelos";

            try{

                $queryModel=$conecta->prepare($sqlModel);
                $queryModel->execute();

                $resultModel = $queryModel->fetchAll(PDO::FETCH_ASSOC);

            }catch(PDOException $erro){
                echo "<div class='alertaN'>Erro ao selecionar modelo.</div>";
            }
        ?>
        
        <h1>Alterar Fases</h1>

        <form name="criarFase" method="POST" action="painel.php">

            <input type="hidden" name="spv" value="alter/fases2">
            
            <input type="hidden" name="faseIdetificacao" value="<?php echo $fase; ?>">

            <div class="cardRight">
                <img src="../cartas/<?php echo $carta; ?>">
            </div>

            <label for="descricao">
                <strong>Descrição:</strong>
                <input type="text" value="<?php echo $descricao; ?>" name="faseDescricao"/>
            </label>
            <label for="carta">
                <strong>Carta:</strong>
                <input type="text" value="<?php echo $cartaDesc; ?>" name="faseCarta"/>
            </label>
            <label for="lbCriterio">
                <strong>Critério:</strong>
            </label>

            <select name="criterio">
                <?php
                    if($mover == 'ambos'){
                        echo "<option value='ambos'>Ambos</option>".
                             "<option value='esquerda'>Esquerda</option>".
                             "<option value='direita'>Direita</option>";
                    }elseif ($mover == 'direita') {
                        echo "<option value='direita'>Direita</option>".
                             "<option value='ambos'>Ambos</option>".
                             "<option value='esquerda'>Esquerda</option>";
                    }elseif ($mover == 'esquerda') {
                        echo "<option value='esquerda'>Esquerda</option>".
                             "<option value='direita'>Direita</option>".
                             "<option value='ambos'>Ambos</option>";
                    }
                ?>
            </select>

            <label for="pontos">
                <strong>Pontos:</strong>
            </label>

            <select name="fasePontos">
                <?php echo "<option value=".$pontos.">".$pontos."</option>";?>

                <?php 
                    for ($i=1; $i<=13 ; $i++) {
                        if($i == $pontos){
                            continue;
                        }else{
                            echo "<option value='".$i."'>$i</option>";
                        }
                    }
                ?>
            </select>

            <label for="perguntaNumber">
                <strong>Nº:</strong>
                <input type="text" value="<?php echo $perguntaId; ?>" name="fasePerguntaNumber"/>
            </label>

            <label for"perguntaTxt">
                <strong>Pergunta:</strong>
                <input type="text" name="fasePerguntaTxt" value="<?php echo $pergunta;?>" disabled="disabled"/>
            </label>

            <label for="modelo">
                <strong>Modelo:</strong>
            </label>

            <select name="faseModelo">
                <?php 

                    echo "<option value=".$modeloId.">".$modelo."</option>";
                
                    foreach ($resultModel as $valModel){
                        if($valModel['modelId'] == $modeloId){
                            continue;
                        }else{
                            echo "<option value='".$valModel['modelId']."'>".ucwords($valModel['modelNome'])."</option>";
                        }
                    }

                ?>
            </select>

            <label for="posicao">
                <strong>Posição:</strong>
                <input type="text" value="<?php echo $posicao;?>" name="fasePosicao"/>
            </label>

            <input type="submit" name="btnCriaFase" style='margin: 0 0 0 780px;' value="Verificar">

        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php 

include_once 'footer.php'; 

}else{
    
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/fases'>";
    exit;
    
}
?>