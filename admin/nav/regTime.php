<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/regoptions">Regras Extras</a> &gt; Regras de Tempo
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">
        <?php 

            $sqlSRT = "SELECT ".
                        "sv_regrastempo.IdRegra as id, ".
                        "sv_modelos.modelNome as modelo, ".
                        "sv_regrastempo.idModelo as modeloId, ".
                        "sv_regrastempo.IdPergunta as perguntaId, ".
                        "sv_perguntas.perguntaTxt as pergunta, ".
                        "sv_regrastempo.tempoInicial as inicio, ".
                        "sv_regrastempo.tempoFinal as final, ".
                        "sv_regrastempo.cicloRepeticao as ciclo, ".
                        "sv_regrastempo.pontuacao as pontos ".
                      "FROM sv_regrastempo ".
                      "LEFT JOIN sv_modelos ON sv_regrastempo.idModelo = sv_modelos.modelId ".
                      "LEFT JOIN sv_perguntas ON sv_regrastempo.IdPergunta = sv_perguntas.perguntaId";

            try{
                $querySRT = $conecta->prepare($sqlSRT);
                $querySRT->execute();

                $resultSRT = $querySRT->fetchAll(PDO::FETCH_ASSOC);

            }catch(PDOException $erroSRT){
                echo "<div class='alertaN'>Erro ao ler informações da regra extra.</div>";
            }

        ?>

        <h1>Regra extra de tempo</h1>

        <table width='100%' border='0' cellspacing='2' cellpadding='0' style='margin-bottom: 10px; float: left;'>
            <tbody style='background: #E7ECEF;'>
                <tr>
                    <th class="titleModel">ID</th>
                    <th class="titleModel">MODELO</th>
                    <th class="titleModel">PERGUNTA</th>
                    <th class="titleModel">INICIO</th>
                    <th class="titleModel">FINAL</th>
                    <th class="titleModel">CICLO</th>
                    <th class="titleModel">PONTO</th>
                    <th colspan="2" class="titleModel">AÇÕES</th>
                </tr>
                <?php foreach ($resultSRT as $valSRT) { ?>

                    <tr>
                        <td style="text-align: center;"><?php echo $valSRT['id'];?></td>
                        <td>
                            <?php

                                if($valSRT['modelo'] == ""){
                                    $valSRT['modelo'] = "Não definido";
                                }

                                echo $valSRT['modelo'];

                            ?>
                        </td>
                        <td><?php echo $valSRT['pergunta'];?></td>
                        <td style="text-align: center;"><?php echo convertTempo($valSRT['inicio']);?></td>
                        <td style="text-align: center;"><?php echo convertTempo($valSRT['final']);?></td>
                        <td style="text-align: center;"><?php echo $valSRT['ciclo']." s";?></td>
                        <td style="text-align: center;"><?php echo $valSRT['pontos'];?></td>
                        <td class="acao">
                            <a href="painel.php?spv=alter/regTime&regra=<?php echo $valSRT['id'];?>">
                                <img src="images/icons/edit_3.png"/>
                            </a>
                        </td>
                        <td class="acao">
                            <a href="painel.php?spv=drop/regTime&regra=<?php echo $valSRT['id'];?>">
                                <img src="images/icons/del_5.png"/>
                            </a>
                        </td>
                    </tr>
                        
                <?php } ?>
            </tbody>
        </table>

        <a class="btnNewFase" href="painel.php?spv=create/regTime">Criar Regra</a>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>   