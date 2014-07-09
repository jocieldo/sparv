<?php 

    include_once 'system/restrito.php';

if(isset($_GET['itemInstrucao'])){

    include_once 'header.php'; 
    include_once '../Connections/config.php'; 

    $itemInstrucao = $_GET['itemInstrucao'];
    $itemInstrucao = (int)$itemInstrucao;

?>

<script type='text/javascript' src="js/alterAssocInstrucao.js"></script>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php'>Modelo</a> &gt; Associando Instruções
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span>João Ilo</span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos"> 

<?php

$sqlSelect = "SELECT ".
                "sv_modelos.modelNome as modelo, ".
                "sv_iteminstrucao.item_modeloId as modeloId, ".
                "sv_iteminstrucao.item_instrucaoId as intrucaoId, ".
                "sv_instrucoes.instrucaoTxt as instrucao, ".
                "sv_iteminstrucao.item_ordem as ordem ".
             "FROM sv_iteminstrucao ".
             "INNER JOIN sv_modelos ON sv_iteminstrucao.item_modeloId = sv_modelos.modelId ".
             "INNER JOIN sv_instrucoes on sv_iteminstrucao.item_instrucaoId = sv_instrucoes.instrucaoId ".
             "WHERE item_id = :item_id ";

try{

    $querySelect = $conecta->prepare($sqlSelect);
    $querySelect->bindValue(":item_id", $itemInstrucao, PDO::PARAM_STR);
    $querySelect->execute();

    $resulRowSelect = $querySelect->rowCount(PDO::FETCH_ASSOC);

}catch(PDOException $erroSelect){
    
}


if($resulRowSelect != 0){

    $resultSelect = $querySelect->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultSelect as $valSelect) {
        $modelo       = $valSelect['modelo'];
        $modeloId       = $valSelect['modeloId'];
        $idInstrucao  = $valSelect['intrucaoId'];
        $txtInstrucao = $valSelect['instrucao'];
        $ordem        = $valSelect['ordem'];
    }

}else{
    
    echo "<meta http-equiv='refresh' content='0;url=painel.php'>";
    exit;
    
}

if(isset($_POST['btnAlterInstrucao'])){

    if(!empty($_POST[tfInstrucao]) and !empty($_POST[tfOrdem])){

        $instrutionId = $_POST[tfInstrucao];
        $orderNumber  = $_POST[tfOrdem];

        $sqlGrava = "UPDATE sv_iteminstrucao SET item_instrucaoId = :item_instrucaoId, item_ordem = :item_ordem WHERE item_id = :item_id";

        try{

            $queryGrava = $conecta->prepare($sqlGrava);
            $queryGrava->bindValue(":item_instrucaoId", $instrutionId, PDO::PARAM_STR);
            $queryGrava->bindValue(":item_ordem", $orderNumber, PDO::PARAM_STR);
            $queryGrava->bindValue(":item_id", $itemInstrucao, PDO::PARAM_STR);
            $queryGrava->execute();

            echo "<meta http-equiv='refresh' content='0;url=painel.php'>";
            exit;

        }catch(PDOException $erroUp){
             echo "<div class='alertaN'>Erro durante a atualização dos dados.</div>";
        }

    }else{
        echo "<div class='alertaN'>Algum campo está vazio. Verifique e tente novamente!</div>";
    }

}

?>

        <h1>Alterando associação de instrução</h1>

        <form name="alterInstrucaoAssoc" method="post" action="">
            <label for="modelo">
                <strong>Modelo:</strong>
                <input name="tfModelo" type="text" value="<?php echo $modelo; ?>" disabled="disabled">
            </label>
            
            <br>

            <label for="instrucao">
                <strong>Instrucão ID:</strong>
                <input name="tfInstrucao" value="<?php echo $idInstrucao;?>" style="width: 25px; text-align: center;" type="text">
                <strong>Ordem:</strong>
                <input style="width: 25px; text-align: center;" value="<?php echo $ordem; ?>" name="tfOrdem" type="text">
                <br>
                <br>
                <strong style="display: block;">Conteúdo da Instrucão:</strong>
                <textarea style="width: 805px; padding: 10px; margin:0;" disabled="disabled"><?php echo $txtInstrucao; ?></textarea>
            </label>

            <input type="submit" name="btnAlterInstrucao" class="newbtn" value="Salvar">

        </form>           
                  
    </div><!-- painel table -->

</div><!-- Content -->
            
<?php 

    include_once 'footer.php'; 

}else{

    echo "<meta http-equiv='refresh' content='0;url=painel.php'>";
    exit;

}
?>