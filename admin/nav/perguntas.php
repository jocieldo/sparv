<?php

    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'header.php';
    
?>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Perguntas
        </div><!-- fecha caminho -->

        <div class="welcome">
           Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagperguntas">
        
        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th> <img src="images/seta_bottom.png" height="10px"/></th>
                <th>Pergunta</th>                
                <th colspan="2">Ações</th>
            </thead>
            <tbody style="background: #E7ECEF;">
                <?php
    
                    $sql_selecionaPerguntas = "SELECT * FROM sv_perguntas";

                    try {

                        $query_selecionaPerguntas = $conecta->prepare($sql_selecionaPerguntas);
                        $query_selecionaPerguntas->execute();

                        $result_selecionaPerguntas = $query_selecionaPerguntas->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount_selecionaPerguntas = $query_selecionaPerguntas->rowCount(PDO::FETCH_ASSOC);

                    } catch (PDOException $erro_selecionaPerguntas) {
                        echo "<div class='erro'>Erro ao selecionar todas instruções</div>";
                    }

                    foreach ($result_selecionaPerguntas as $res_selecionaPerguntas){
                        $perguntaId = $res_selecionaPerguntas['perguntaId'];
                        $perguntaTxt = $res_selecionaPerguntas['perguntaTxt'];                        
                ?>
                <tr>
                    <td class="points" ><?php echo $perguntaId;?></td>
                    <td class="fases"><?php echo $perguntaTxt;?></td>                    
                    <td class="acao">
                        <a href="painel.php?spv=alter/perguntas&id=<?php echo $perguntaId; ?>">
                            <img src="images/icons/edit_3.png"/>
                        </a>
                    </td>
                    <td class="acao">
                        <a href="painel.php?spv=drop/perguntas&id=<?php echo $perguntaId; ?>">
                            <img src="images/icons/del_5.png"/>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <a class="btnNewFase" href="painel.php?spv=create/perguntas"> Nova Pergunta</a>
        
    </div><!-- painel pagfases -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>