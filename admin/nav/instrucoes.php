<?php

    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'header.php';
    
?>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Instruções
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
                <th>Instrução</th>                
                <th colspan="2">Ações</th>
            </thead>
            <tbody style="background: #E7ECEF;">
                <?php
    
                    $sql_selecionaInstrucoes = "SELECT * FROM sv_instrucoes";

                    try {

                        $query_selecionaInstrucoes = $conecta->prepare($sql_selecionaInstrucoes);
                        $query_selecionaInstrucoes->execute();

                        $result_selecionaInstrucoes = $query_selecionaInstrucoes->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount_selecionaInstrucoes = $query_selecionaInstrucoes->rowCount(PDO::FETCH_ASSOC);

                    } catch (PDOException $erro_selecionaInstrucoes) {
                        echo "<div class='erro'>Erro ao selecionar todas instruções</div>";
                    }

                    foreach ($result_selecionaInstrucoes as $res_selecionaInstrucoes){
                        $instrucaoId = $res_selecionaInstrucoes['instrucaoId'];
                        $instrucaoTxt = $res_selecionaInstrucoes['instrucaoTxt'];                        
                ?>
                <tr>
                    <td class="points" ><?php echo $instrucaoId; ?></td>
                    <td class="fases"><?php echo $instrucaoTxt; ?></td>                    
                    <td class="acao">
                        <a href="painel.php?spv=alter/instrucoes&id=<?php echo $instrucaoId; ?>">
                            <img title="Editar Instrução" src="images/icons/edit_3.png"/>
                        </a>
                    </td>
                    <td class="acao">
                        <a href="painel.php?spv=drop/instrucoes&id=<?php echo $instrucaoId; ?>">
                            <img title="Excluir Instrução" src="images/icons/del_5.png"/>
                        </a>
                    </td>
                </tr>
                    <?php } ?>
            </tbody>
        </table>
        
        <a class="btnNewFase" href="painel.php?spv=create/instrucoes"> Nova Instrução</a>
        
    </div><!-- painel pagfases -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>
            