<?php 

include_once 'system/restrito.php';
include_once '../Connections/config.php';
include_once 'header.php';

?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/fases'>Fases</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagfases">
        
        <h1>Criando Fases</h1>

        <form name="criarFase" method="POST" action="painel.php">

            <input type="hidden" name="spv" value="create/fases2">

             <div class="cardRight">
             </div>
            
            <label for="descricao">
                <strong>Descrição:</strong>
                <input type="text" name="faseDescricao"/>
            </label>
            <label for="carta">
                <strong>Carta:</strong>
                <input type="text" name="faseCarta"/>
            </label>

            <label for="lbCriterio">
                <strong>Critério:</strong>
            </label>

            <select name="criterio">
                <option value="ambos">Ambos</option>
                <option value="esquerda">Esquerda</option>
                <option value="direita">Direita</option>
            </select>

            <label for="pontos">
                <strong>Pontos:</strong>
            </label>

            <select name="fasePontos">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
            </select>

            <label for="perguntaNumber">
                <strong>Nº:</strong>
                <input type="text" name="fasePerguntaNumber"/>
            </label>

            <label for"perguntaTxt">
                <strong>Pergunta:</strong>
                <input type="text" name="fasePerguntaTxt" value="" disabled="disabled"/>
            </label>

            <label for="modelo">
                <strong>Modelo:</strong>
            </label>
            <select name="faseModelo">
                <?php 

                    $sqlSelecionaModelo = "SELECT * FROM sv_modelos";

                    try{

                        $querySelecionaModelo = $conecta->prepare($sqlSelecionaModelo);
                        $querySelecionaModelo->execute();

                        $rowResultSelecionaModelo = $querySelecionaModelo->rowCount(PDO::FETCH_ASSOC);

                        if($rowResultSelecionaModelo == 0){

                            echo "<option>Ocorreu um erro</option>";

                        }

                        $ResultSelecionaModelo = $querySelecionaModelo->fetchAll(PDO::FETCH_ASSOC);

                    }catch(PDOException $erroSelecionaModelo){

                        echo "<div class='alertaN'>Erro ao selecionar modelo</div>";

                    }

                    echo "<option value=' '>Modelo</option>";

                    foreach ($ResultSelecionaModelo as $valSM) {
                        echo "<option value='".$valSM['modelId']."'> ".$valSM['modelNome']."</option>";
                    }
                ?>
            </select>

                <input name="fasePosicao" type="hidden" value="<?php 

                    $sqlSelecionaPosicao = 'SELECT  MAX(sv_fases.fasePosicao) as maximo FROM sv_fases';

                    try{

                        $querySelecionaPosicao = $conecta->prepare($sqlSelecionaPosicao);
                        $querySelecionaPosicao->execute();

                        $resSelecionaPosicao = $querySelecionaPosicao->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($resSelecionaPosicao as $valSPo) {
                            echo (($valSPo['maximo'])+1);
                        }

                    }catch(PDOException $erroSelcionaPoscao){

                        echo "Erro";

                    }
                ?>
                "/>

            <input type="submit" name="btnCriaFase2" value="Verificar">
        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>
