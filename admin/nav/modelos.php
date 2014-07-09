<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>

<script src="js/modelo_table.js" type="text/javascript"></script>

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
                    //Selecionando modelos
                    $sqlSM = "SELECT * FROM sv_modelos";

                    try{

                        $querySM = $conecta->prepare($sqlSM);
                        $querySM->execute();

                        //armazenando em um array o resultado
                        $resultSM = $querySM->fetchAll(PDO::FETCH_ASSOC);

                        //contano a quantidade de resgistros retornados
                        $rowResultSM = $querySM->rowCount(PDO::FETCH_ASSOC);

                    }catch(PDOException $erroSM){
                        echo "<div class='alertaN'>Erro ao selecionar dados de modelo!</div>";
                    }

                    /*
                        Verificado se há algum registro retornado caso haja ele 
                        exibe os dados. Caso contrário, ele exibe o erro que está 
                        na clausula ELSE 
                    */

                    if ($rowResultSM != 0) {

                        foreach ($resultSM as $valSM) {
                            
                            $modeloId   = $valSM['modelId'];
                            $modeloNome = $valSM['modelNome'];
                            $modelTempo = $valSM['modelTempoId'];
                ?>               
                    <table width='100%' border='0' cellspacing='2' cellpadding='0' style='float: left;'>
                        <tbody style='background: #E7ECEF;'>
                            <tr class="titleModel">
                                <th data-href="" colspan='9' style="text-align: left; padding: 0px"><a style="color: #fff; text-decoration: none;" href="painel.php?spv=nav/instrucoesAssoc&modelo=<?php echo $modeloId; ?>"><?php echo $modeloNome; ?></a></th>
                            </tr>
                            <tr style='text-transform: uppercase; text-align: center; background: #CFD8DE'>
                                <td style='border-radius: 0'>id</td>
                                <td style='border-radius: 0'>descrição</td>
                                <td style='border-radius: 0'>carta</td>
                                <td style='border-radius: 0'>critério</td>
                                <td style='border-radius: 0'>p</td>
                                <td style='border-radius: 0'>pergunta</td>
                                <td style='border-radius: 0'>o</td>
                                <td  style='border-radius: 0' colspan='2'>ações</td>
                            </tr>
                            <?php 

                                //Selecionando as fases para o modelo e do modelo
                                $sqlSFM = "SELECT ".
                                            "sv_fases.faseId as fase, ".
                                            "sv_fases.faseDescricao as descricao, ".
                                            "sv_fases.fasePonto as ponto, ".
                                            "sv_fases.fasePGMove as criterio, ".
                                            "sv_fases.fasePosicao as posicao, ".
                                            "sv_cartas.cartaDirectory as carta, ".
                                            "sv_perguntas.perguntaTxt as pergunta ".
                                          "FROM sv_fases ".
                                            "INNER JOIN sv_cartas ON sv_fases.faseCartaId = sv_cartas.cartaID ".
                                            "INNER JOIN sv_perguntas ON sv_fases.fasePerguntaId = sv_perguntas.perguntaId ".
                                          "WHERE sv_fases.modelId = ".$modeloId." ORDER BY sv_fases.fasePosicao";

                                $querySFM = $conecta->prepare($sqlSFM);
                                $querySFM->execute();

                                //Armazenando o valor num array para depois fazer a exibição
                                $resultSFM = $querySFM->fetchAll(PDO::FETCH_ASSOC);

                                //Contando a quantidade de registros retornados
                                $rowResultSFM = $querySFM->rowCount(PDO::FETCH_ASSOC);

                                //verificando se foi retornado algum registro
                                if($rowResultSFM !=0){

                                    foreach ($resultSFM as $valSFM) {
                                        
                                        $fase      = $valSFM['fase'];
                                        $descricao = $valSFM['descricao'];
                                        $criterio  = $valSFM['criterio'];
                                        $ponto     = $valSFM['ponto'];
                                        $carta     = $valSFM['carta'];
                                        $pergunta  = $valSFM['pergunta'];
                                        $posicao   = $valSFM['posicao'];

                                        ?>

                                        <tr>
                                            <td id="idFase"><?php echo $fase; ?></td>
                                            <td><?php echo $descricao; ?></td>
                                            <td id="imgCarta"><img src="../cartas/<?php echo $carta; ?>"/></td>
                                            <td id="mover"><?php echo ucfirst($criterio); ?></td>
                                            <td id="pontos" style="text-align: center;"><?php echo $ponto; ?></td>
                                            <td><?php echo $pergunta; ?></td>
                                            <td id="ordem"><?php echo $posicao; ?></td>
                                            <td class="acao"><a href="painel.php?spv=alter/fases&fase=<?php echo $fase; ?>&mod=true"><img src="images/icons/edit_3.png"/></a></td>
                                            <td class="acao"><a href="painel.php?spv=drop/fases&fase=<?php echo $fase; ?>&mod=true"><img src="images/icons/del_5.png"/></a></td>
                                        </tr>

                                        <?php

                                    }?>
                                        <tr style='text-transform: uppercase; text-align: center; background: #CFD8DE'>
                                            <td style='border-radius: 0' colspan='9'>Regra Extra de Tempo</td>
                                        </tr>
                                <?php

                                    //Verificando a existência de regras extras de tempo e as exibindo caso exista

                                    $sqlSRT = "SELECT ".
                                                    "sv_regrastempo.IdRegra as regra, ".
                                                    "sv_perguntas.perguntaTxt as pergunta, ".
                                                    "sv_regrastempo.tempoInicial as inicio, ".
                                                    "sv_regrastempo.tempoFinal as final, ".
                                                    "sv_regrastempo.cicloRepeticao as ciclo, ".
                                                    "sv_regrastempo.pontuacao as ponto ".
                                               "FROM sv_regrastempo ".
                                                    "INNER JOIN sv_perguntas ON sv_perguntas.perguntaId = sv_regrastempo.IdPergunta ".
                                               "WHERE sv_regrastempo.idModelo = :idModelo ";

                                    try{
                                        $querySRT = $conecta->prepare($sqlSRT);
                                        $querySRT->bindValue(":idModelo", $modeloId, PDO::PARAM_STR);
                                        $querySRT->execute();

                                        $resulSRT= $querySRT->fetchAll(PDO::FETCH_ASSOC);

                                        $rowResulSRT= $querySRT->rowCount(PDO::FETCH_ASSOC);

                                    }catch(PDOException $erroSRT){
                                        echo "<div class='alertaN'>Erro ao selecionar regras extras de tempo.</div>";
                                    }

                                    if($rowResulSRT != 0){
                                        ?>
                                            
                                        <tr>
                                            <td style='text-align: center; background: #CFD8DE;'>ID</td>
                                            <td style='text-align: left; background: #CFD8DE;' colspan='4'>PERGUNTA</td>
                                            <td style='text-align: center; background: #CFD8DE;' >INICIO</td>
                                            <td style='text-align: center; background: #CFD8DE;' >FIM</td>
                                            <td style='text-align: center; background: #CFD8DE;' >CC</td>
                                            <td style='text-align: center; background: #CFD8DE;'>PT</td>
                                        </tr>

                                        <?php

                                        foreach ($resulSRT as $valSRT) {
                                            
                                            $regraId       = $valSRT['regra'];
                                            $regraPergunta = $valSRT['pergunta'];
                                            $regraInicio   = $valSRT['inicio'];
                                            $regraFinal    = $valSRT['final'];
                                            $regraCiclo    = $valSRT['ciclo'];
                                            $regraPonto    = $valSRT['ponto'];

                                            ?>

                                            <tr>
                                                <td style="text-align: center"><?php echo $regraId;?></td>
                                                <td colspan='4'><?php echo $regraPergunta;?></td>
                                                <td style="text-align: center"><?php echo convertTempo($regraInicio);?></td>
                                                <td style="text-align: center"><?php echo convertTempo($regraFinal);?></td>
                                                <td style="text-align: center"><?php echo $regraCiclo." s";?></td>
                                                <td style="text-align: center"><?php echo $regraPonto;?></td>
                                            </tr>

                                            <?php
                                        }

                                    }else{
                                        echo "<tr>".
                                            "<td colspan='9' style='padding: 5px; text-align: center; color: #fff; background: #bf3030;'>Não há regra extra de tempo associada a este modelo.</td>".
                                         "</tr>";
                                    }?>
                                    
                                    <tr style='text-transform: uppercase; text-align: center; background: #CFD8DE'>
                                            <td style='border-radius: 0' colspan='9'>Regra Extra de Movimentação</td>
                                    </tr>

                                <?php

                                //Verificano a existencia de regras extras de movimento
                                $sqlSRM = "SELECT ".
                                               "sv_regrasmovimento.IdRegra as regra, ".
                                               "sv_perguntas.perguntaTxt as pergunta, ".
                                               "sv_regrasmovimento.posicao as criterio, ".
                                               "sv_regrasmovimento.pontuacao as ponto, ".
                                               "sv_regrasmovimento.tempoInicial as inicio, ".
                                               "sv_regrasmovimento.tempoFinal as final ".
                                           "FROM sv_regrasmovimento ".
                                               "INNER JOIN sv_perguntas ON sv_regrasmovimento.IdPergunta = sv_perguntas.perguntaId ".
                                           "WHERE sv_regrasmovimento.idModelo = :idModelo";

                                try{

                                    $querySRM = $conecta->prepare($sqlSRM);
                                    $querySRM->bindValue(":idModelo", $modeloId, PDO::PARAM_STR);
                                    $querySRM->execute();

                                    $resultSRM = $querySRM->fetchAll(PDO::FETCH_ASSOC);

                                    $rowResultSRM = $querySRM->rowCount(PDO::FETCH_ASSOC);

                                    if($rowResultSRM != 0){
                                    ?>

                                    <tr>
                                        <td>ID</td>
                                        <td colspan='4'>PERGUNTA</td>
                                        <td>CRITÉRIO</td>
                                        <td>PONTO</td>
                                        <td>INICIO</td>
                                        <td>FIM</td>
                                    </tr>

                                    <?php

                                        foreach ($resultSRM as $valSRM) {
                                            $movId       = $valSRM['regra'];
                                            $movPergunta = $valSRM['pergunta'];
                                            $movCriterio = $valSRM['criterio'];
                                            $movPonto    = $valSRM['ponto'];
                                            $movInicio   = $valSRM['inicio'];
                                            $movFim      = $valSRM['final'];
                                            ?>

                                                <tr>
                                                    <td><?php echo $movId;?></td>
                                                    <td colspan='4'><?php echo $movPergunta;?></td>
                                                    <td><?php echo $movCriterio;?></td>
                                                    <td><?php echo $movPonto;?></td>
                                                    <td><?php echo convertTempo($movInicio);?></td>
                                                    <td><?php echo convertTempo($movFim);?></td>
                                                </tr>

                                            <?php
                                        }

                                    }else{

                                        echo "<tr>".
                                            "<td colspan='9' style='padding: 5px; text-align: center; color: #fff; background: #bf3030;'>Não há regra extra de movimentação associada a este modelo.</td>".
                                         "</tr>";

                                    }

                                }catch(PDOException $erroSRM){
                                    echo "<div class='alertaN'>Erro ao selecionar dados de regra de movimentação.</div>";
                                }

                                

                                }else{

                                    echo "<tr>".
                                            "<td colspan='9'>Não há fases associada a este modelo.</td>".
                                         "</tr>";

                                }

                                
                        }
                        
                    }else{
                        echo "<div class='alertaN'>Nenhum modelo encontrado.</div>";
                    }
                ?>
            </tbody>
        </table>
        
        <a class="btnNewFase" href="painel.php?spv=create/modelos">Novo Modelo</a>
        <br>
        <a class="btnNewFase" style="margin: 0 391px;" href="painel.php?spv=nav/dropModel">Excluir Modelo</a>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>           