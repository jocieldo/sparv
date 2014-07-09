<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/regoptions">Regras Extras</a> &gt; Regras de Movimentação
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">
        <?php 

            $sqlSRM = "SELECT ". 
                        "sv_regrasmovimento.IdRegra as id, ".
                        "sv_regrasmovimento.IdPergunta as perguntaId, ".
                        "sv_perguntas.perguntaTxt as pergunta, ".
                        "sv_regrasmovimento.idModelo as modeloId, ".
                        "sv_modelos.modelNome as modelo, ".
                        "sv_regrasmovimento.posicao as criterio, ".
                        "sv_regrasmovimento.pontuacao as pontos, ".
                        "sv_regrasmovimento.tempoInicial as inicio, ".
                        "sv_regrasmovimento.tempoFinal as final ".
                     "FROM sv_regrasmovimento ".
                     "LEFT JOIN sv_perguntas ON sv_regrasmovimento.IdPergunta = sv_perguntas.perguntaId ".
                     "LEFT JOIN sv_modelos ON sv_regrasmovimento.idModelo = sv_modelos.modelId";

            try{

                $querySRM = $conecta->prepare($sqlSRM);
                $querySRM->execute();

                $resultSRM = $querySRM->fetchAll(PDO::FETCH_ASSOC);

            }catch(PDOException $erroSRM){
                echo "<div class='alertaN'>Erro ao ler informações da regra extra.</div>";
            }

        ?>

        <h1>Regra extra de movimento</h1>

        <table width='100%' border='0' cellspacing='2' cellpadding='0' style='margin-bottom: 10px; float: left;'>
            <tbody style='background: #E7ECEF;'>
                <tr>
                    <th class="titleModel">ID</th>
                    <th class="titleModel">MODELO</th>
                    <th class="titleModel">PERGUNTA</th>
                    <th class="titleModel">INICIO</th>
                    <th class="titleModel">FINAL</th>
                    <th class="titleModel">CRITÉRIO</th>
                    <th class="titleModel">PONTO</th>
                    <th colspan="2" class="titleModel">AÇÕES</th>
                </tr>
                <?php foreach ($resultSRM as $valSRM) { ?>

                    <tr>
                        <td style="text-align: center;"><?php echo $valSRM['id'];?></td>
                        <td>
                            <?php

                                if($valSRM['modelo'] == ""){
                                    $valSRM['modelo'] = "Não definido";
                                }

                                echo $valSRM['modelo'];

                            ?>
                        </td>
                        <td><?php echo $valSRM['pergunta'];?></td>
                        <td style="text-align: center;"><?php echo convertTempo($valSRM['inicio']);?></td>
                        <td style="text-align: center;"><?php echo convertTempo($valSRM['final']);?></td>
                        <td style="text-align: center;"><?php echo ucfirst($valSRM['criterio']);?></td>
                        <td style="text-align: center;"><?php echo $valSRM['pontos'];?></td>
                        <td class="acao">
                            <a href="painel.php?spv=alter/regMove&regra=<?php echo $valSRM['id'];?>">
                                <img src="images/icons/edit_3.png"/>
                            </a>
                        </td>
                        <td class="acao">
                            <a href="painel.php?spv=drop/regMove&regra=<?php echo $valSRM['id'];?>">
                                <img src="images/icons/del_5.png"/>
                            </a>
                        </td>
                    </tr>
                        
                <?php } ?>
            </tbody>
        </table>

        <a class="btnNewFase" href="painel.php?spv=create/regMove">Criar Regra</a>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>   