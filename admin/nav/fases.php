<?php 

    include_once 'system/restrito.php'; 
    include_once 'header.php'; 
    include_once '../Connections/config.php';
    
?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Fases
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagfases">
            
        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
            <th> <img src="images/seta_bottom.png" height="10px"/></th>
                <th>Fase</th>
                <th>Carta</th>
                <th>critério</th>
                <th>pergunta</th>
                <th style="padding: 0 10px;">PT</th>
                <th>modelo</th>
                <th colspan="2">Ações</th>
            </thead>
            <tbody style="background: #E7ECEF;">
                <?php 
                    //SQL para selecionar fases
                    $sqlSF = "SELECT ".
                             "sv_fases.faseId as id, ".
                             "sv_fases.faseDescricao as descricao, ".
                             "sv_fases.fasePonto as pontos, ".
                             "sv_fases.fasePGMove as mover, ".
                             "sv_modelos.modelNome as modelo, ".
                             "sv_perguntas.perguntaTxt as pergunta, ".
                             "sv_cartas.cartaDirectory as carta ".
                             "FROM ".
                             "sv_fases ".
                             "INNER JOIN sv_modelos ON sv_fases.modelId = sv_modelos.modelId ".
                             "INNER JOIN sv_perguntas ON sv_fases.fasePerguntaId = sv_perguntas.perguntaId ".
                             "INNER JOIN sv_cartas ON sv_fases.faseCartaId = sv_cartas.cartaID ".
                             "ORDER BY sv_fases.faseId";

                    $querySF = $conecta->prepare($sqlSF);                             
                    $querySF->execute();

                    $resultSF = $querySF->fetchAll(PDO::FETCH_ASSOC);
                    $rowResultSF = $querySF->rowCount(PDO::FETCH_ASSOC);

                    if($rowResultSF == 0){
                        //imprimndo erro de quando a consulta não retornar nada
                    }

                    foreach ($resultSF as $valSF) {
                        $fase      = $valSF['id'];
                        $descricao = $valSF['descricao'];
                        $pontos    = $valSF['pontos'];
                        $mover     = ucfirst($valSF['mover']);
                        $modelo    = ucwords($valSF['modelo']);
                        $pergunta  = $valSF['pergunta'];
                        $carta     = $valSF['carta'];
                    
                ?>
                <tr>
                    <td class="points"><?php echo $fase; ?></td>
                    <td><?php echo $descricao; ?></td>
                    <td><img src="../cartas/<?php echo $carta; ?>"/></td>
                    <td><?php echo $mover; ?></td>
                    <td><?php echo $pergunta; ?></td>
                    <td style="text-align: center"><?php echo $pontos; ?></td>
                    <td style="padding: 0 10px;"><?php echo $modelo; ?></td>
                    <td class="acao"><a href="painel.php?spv=alter/fases&fase=<?php echo $fase; ?>"><img src="images/icons/edit_3.png"/></a></td>
                    <td class="acao"><a href="painel.php?spv=drop/fases&fase=<?php echo $fase; ?>"><img src="images/icons/del_5.png"/></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <a class="btnNewFase" href="painel.php?spv=create/fases">Nova Fase</a>
        
    </div><!-- painel pagfases -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>