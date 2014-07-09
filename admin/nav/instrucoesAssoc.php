<?php 

if(isset($_GET['modelo']) and !empty($_GET['modelo'])){

    $modelId = $_GET['modelo'];
    $modelId = (int)$modelId;

    include_once 'header.php';
    include_once '../Connections/config.php';
    include_once "funcs/functions.php";
    include_once 'system/restrito.php';

?>   

<script type="text/javascript" src="js/assocInstrucao.js"></script>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Modelos
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">
        <?php

            /*if(isset($_POST['btnCriaModelo'])){

                $arrInstrucao = $_POST['idInstrucao'];
                $arrOrderm    = $_POST['ordem'];

                try{

                    $sql = "INSERT INTO sv_iteminstrucao (item_modeloId, item_instrucaoId, item_ordem) VALUES ".
                                                        "(:item_modeloId, :item_instrucaoId, :item_ordem)";

                    $query = $conecta->prepare($sql);                                                        

                    for ($i=1; $i < count($arrInstrucao) ; $i++) { 

                        $query->bindValue(":item_modeloId", $modelId, PDO::PARAM_STR);
                        $query->bindValue(":item_instrucaoId", $arrInstrucao[$i], PDO::PARAM_STR);
                        $query->bindValue(":item_ordem", $arrOrderm[$i], PDO::PARAM_STR);
                        $query->execute();
                        
                    }

                }catch(PDOException $erro){
                    echo "<div class='alertaN'>Erro ao associar instruções!</div>";
                }

            }*/

        //Verifica se a ação de submit no form foi efetuada
        if (isset($_POST['btnCriaModelo'])) {

            //Percorre o vetor que é passado com todas informações e as grava
            for ($i=0; $i <count($_POST['ordem']) ; $i++) {

                //Verifica se todas a variáveis não estão vazias
                if(!empty($_POST['ordem'][$i]) and !empty($_POST['idInstrucao'][$i])){

                    //Armazena os valores correntes no loop
                    $ordem     = trim($_POST['ordem'][$i]);
                    $instrucao = trim($_POST['idInstrucao'][$i]);
                    // #retirado $modelo    = trim($_POST['modelo'][$i]);

                    //SQL para verificar se há algum registro na mesma posição e com mesmo modelo
                    $sqlVerifica = "SELECT * FROM sv_iteminstrucao ".
                                   "WHERE ".
                                   "item_modeloId = :modelo AND ". 
                                   "item_ordem = :ordem ";

                    try {

                        //Execução da verificação de registro de mesma posição e modelo
                        $queryVerifica=$conecta->prepare($sqlVerifica);
                        $queryVerifica->bindValue(":modelo", $modelId, PDO::PARAM_STR);
                        $queryVerifica->bindValue(":ordem", $ordem, PDO::PARAM_STR);
                        $queryVerifica->execute();

                        //Armazena o valor retornado pela consulta
                        $resultadoVerifica = $queryVerifica->rowCount(PDO::FETCH_ASSOC);
                                                
                    } catch (PDOException $erro) {
                        echo "<div class='alertaN'>Erro ao realizar uma verificação!</div>";
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
                            $queryUpdate->bindValue(":modeloU", $modelId, PDO::PARAM_STR);
                            $queryUpdate->bindValue(":ordemU", $ordem, PDO::PARAM_STR);
                            $queryUpdate->execute();

                        } catch (PDOException $erroD) {
                            echo "<div class='alertaN'>Erro durante uma gravação não normal!</div>";
                        }

                        //Depois de ter reorganizado os registros de acordo com a posição desejada faz a inserção no banco de dados
                        $sqlGravaAlterado = "INSERT INTO ".
                                            "sv_iteminstrucao (item_modeloId, item_instrucaoId, item_ordem) ".
                                            "VALUES (:modeloY, :instrucaoY, :ordemY)";                      

                        try {
                            //Execução da gravação dos registros no banco de dados
                            $queryGravaAlterado=$conecta->prepare($sqlGravaAlterado);
                            $queryGravaAlterado->bindValue(":modeloY", $modelId, PDO::PARAM_STR);
                            $queryGravaAlterado->bindValue(":instrucaoY", $instrucao, PDO::PARAM_STR);
                            $queryGravaAlterado->bindValue(":ordemY", $ordem, PDO::PARAM_STR);
                            $queryGravaAlterado->execute();

                        } catch (PDOException $e) {
                            echo "<div class='alertaN'>Erro ao gravar instrução alterada.</div>";
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
                            $queryGravaNormal->bindValue(":modeloG", $modelId, PDO::PARAM_STR);
                            $queryGravaNormal->bindValue(":instrucaoG", $instrucao, PDO::PARAM_STR);
                            $queryGravaNormal->bindValue(":ordemG", $ordem, PDO::PARAM_STR);
                            $queryGravaNormal->execute();

                        } catch (PDOException $erroGravaNormal) {
                            echo "<div class='alertaN'>Erro ao gravar normalmente!</div>";
                        }
                    }
                }   
            }
        }
        ?>

        <h1>Criando Modelos</h1>

        <form name="criaModelo" method='POST' action=''>

            <h2>Associando Instruções</h2>

            <img src="images/4.png" class="btnNewInst btnHover" title="Inserir nova instrução" />

            <label id="meuTemplate" for="criaAssoc" class="instAssoc" style="display:none">

                <strong>ID:</strong>
                <input name="idInstrucao[]" type="text">
                <strong>Ordem:</strong>
                <input name="ordem[]"type="text">

                <span>
                    <strong class="instTxt">Instrução:</strong>
                    <textarea disabled="disabled"></textarea>
                </span>

                <img src="images/1.png" title="Remover instrução" class="botaoExcluir" >

            </label>  

            <input type="submit" name="btnCriaModelo" class="newbtn" value="Criar"> 

        </form>

    <?php 

        //Verifaicando a existencia de instruções asssociadas a este modelo
        $sqlSI = "SELECT ".
                   "sv_iteminstrucao.item_id as id, ".
                   "sv_iteminstrucao.item_ordem as ordem, ".
                   "sv_instrucoes.instrucaoTxt as instrucao ".
                 "FROM sv_iteminstrucao ".
                 "INNER JOIN sv_modelos ON sv_iteminstrucao.item_modeloId = sv_modelos.modelId ".
                 "INNER JOIN sv_instrucoes ON sv_iteminstrucao.item_instrucaoId = sv_instrucoes.instrucaoId ".
                 "WHERE sv_modelos.modelId = :modeloIde ORDER BY ordem";

        try{

            $querySI = $conecta->prepare($sqlSI);
            $querySI->bindValue(":modeloIde", $modelId, PDO::PARAM_STR);
            $querySI->execute();

            $resultSIRow = $querySI->rowCount(PDO::FETCH_ASSOC);

            $resultSI = $querySI->fetchAll(PDO::FETCH_ASSOC);

            ?>
            
                <table width='100%' border='0' cellspacing='2' cellpadding='0' style='margin-top: 10px; margin-bottom:10px; float: left;'>
                    <thead style="background: #70909D; color:#fff;">
                        <th style="padding: 5px;">Nº</th>
                        <th>INSTRUCÃO</th>
                        <th colspan='2'>Ações</th>
                    </thead>
                    <tbody style='background: #E7ECEF;'>
            
            <?php

            if($resultSIRow != 0){
                
                foreach ($resultSI as $valueSI) {

            ?>

                        <tr>
                            <td><?php echo $valueSI['ordem']; ?></td>
                            <td title="Esta instrução tem o ID: <?php echo $valueSI['id']; ?>"><?php echo $valueSI['instrucao']?></td>
                            <td class="acao">
                                <a href="painel.php?spv=alter/instrucoesAssoc&itemInstrucao=<?php echo $valueSI['id'];?>">
                                    <img src="images/icons/edit_3.png"/>
                                </a>
                            </td>
                            <td class="acao">
                                <a href="painel.php?spv=drop/instrucaoAssoc&modelo=<?php echo $modelId;?>&ordem=<?php echo $valueSI['ordem']?>">
                                    <img src="images/icons/del_5.png"/>
                                </a>
                            </td>
                        </tr>
                
                <?php       
                }

            }else{
                echo "<tr><td colspan='3' style='text-align: center; padding: 3px; background: #bf3030; color: #fff'>Nenhuma instrução associada a este modelo.</td></tr>";
            }

                ?>
                    </tbody>
                </table>    
    <?php

        }catch(PDOException $erroSI){
            echo "<div class='alertaN'>Erro ao consultar instruções!</div>";
        }

    ?>

    <h1>Detalhes das definições de tempo</h1>

    <table width='100%' border='0' cellspacing='2' cellpadding='0' style='margin-top: 10px; margin-bottom: 10px; float: left;'>
        <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
            <th> <img src="images/seta_bottom.png" height="10px"/></th>
            <th>ordem</th>
            <th>tempo</th>        
            <th>visibilidade inicial</th>        
            <th>mudando em</th>
            <th style="padding: 0 5px">Alterar</th>
        </thead>        
        <tbody style="background: #E7ECEF; text-align: center;">
                <?php                
    
                    $sql_selecionaTempo = "select sv_tempo.* from sv_modelos " 
                                         ."INNER JOIN sv_tempo ON sv_modelos.modelTempoId = sv_tempo.tempoId "
                                         ."WHERE sv_modelos.modelId = :valor";

                    try {

                        $query_selecionaTempo = $conecta->prepare($sql_selecionaTempo);
                        $query_selecionaTempo->bindValue(":valor", $modelId, PDO::PARAM_STR);
                        $query_selecionaTempo->execute();

                        $result_selecionaTempo = $query_selecionaTempo->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount_selecionaTempo = $query_selecionaTempo->rowCount(PDO::FETCH_ASSOC);

                    } catch (PDOException $erro_selecionaTempo) {
                        echo "<div class='erro'>Erro ao selecionar todas instruções</div>";
                    }

                    foreach ($result_selecionaTempo as $res_selecionaTempo){
                        $tempoId = $res_selecionaTempo['tempoId'];
                        $tempoOrdem = $res_selecionaTempo['tempoOrdem'];  
                        $tempoTotal = $res_selecionaTempo['tempoTotal'];
                        $tempoVisao = $res_selecionaTempo['tempoVisao'];
                        $tempoMomento = $res_selecionaTempo['tempoMomento'];

                        if ($tempoVisao == 'normal') {
                            $tempoVisao = "Visível";
                        }elseif ($tempoVisao == 'invisivel') {
                            $tempoVisao = 'Invisível';
                        }else{
                            $tempoVisao = 'Erro';
                        }
                                                                      
                ?>
                <tr>
                    <td><?php echo $tempoId;?></td>
                    <td><?php echo $tempoOrdem;?></td>
                    <td><?php echo convertTempo($tempoTotal);?></td>
                    <td><?php echo $tempoVisao;?></td>
                    <td><?php if ($tempoMomento == null) { echo "---------------"; }else{ echo $tempoMomento; }?></td>
                    <td class="acao"><a href="painel.php?spv=alter/tempo&alt=<?php echo $tempoId;?>"><img src="images/icons/edit_3.png"/></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table><!-- Tabela das definições de tempo -->

        <a href="painel.php?spv=alter/timeModel&modelo=<?php echo $modelId; ?>" style="text-decoration:none; padding: 5px 10px;" class="newbtn">Alterar Regra de Tempo</a>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php 
    
    include_once 'footer.php'; 
}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php'>";
    exit;
}
?>