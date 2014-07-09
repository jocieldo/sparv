<?php 

include_once 'system/restrito.php';

if (!isset($_POST['btnCriaFase2'])){
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/fases'>";
    exit;
}


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
        <?php 
            if(!empty($_POST['faseDescricao']) and !empty($_POST['faseCarta']) and !empty($_POST['criterio']) and !empty($_POST['fasePerguntaNumber']) and !empty($_POST['faseModelo'])){

                $sqlSelecionaModelo = "Select * from sv_modelos WHERE modelId = :modelNome";

                try{
                    $querySelecionaModel = $conecta->prepare($sqlSelecionaModelo);
                    $querySelecionaModel->bindValue(":modelNome", $_POST['faseModelo'], PDO::PARAM_STR);
                    $querySelecionaModel->execute();

                    $resultSelecionaModelRow = $querySelecionaModel->rowCount(PDO::FETCH_ASSOC);  

                    $resultSelecionaModel = $querySelecionaModel->fetchAll(PDO::FETCH_ASSOC);  

                }catch(PDOException $erroSelecionaModelo){
                    echo "<div class='alertaN'>Erro ao consultar modelo.</div>".$erroSelecionaModelo->getMessage();
                }

                if ($resultSelecionaModelRow == 0) {
                    echo "<div class='alertaN'>Modelo inválido.</div>";
                    $modeloValue = "Erro";
                }

                foreach ($resultSelecionaModel as $valRSM) {
                    $modeloValue = $valRSM['modelNome'];
                    $modeloIdValue = $valRSM['modelId'];
                }

                //Selecionando pergunta

                 $sqlSelecionaPergunta = "Select perguntaId, perguntaTxt from sv_perguntas WHERE perguntaId = :perguntaId";

                try{
                    $querySelecionaPergunta = $conecta->prepare($sqlSelecionaPergunta);
                    $querySelecionaPergunta->bindValue(":perguntaId", $_POST['fasePerguntaNumber'], PDO::PARAM_STR);
                    $querySelecionaPergunta->execute();

                    $resultSelecionaPerguntaRow = $querySelecionaPergunta->rowCount(PDO::FETCH_ASSOC);  

                    $resultSelecionaPergunta = $querySelecionaPergunta->fetchAll(PDO::FETCH_ASSOC);  

                }catch(PDOException $erroSelecionaModelo){
                    echo "<div class='alertaN'>Erro ao consultar modelo.</div>";
                }

                if ($resultSelecionaPerguntaRow == 0) {
                    echo "<div class='alertaN'>Perguta inválida.</div>";
                    $perguntaValue = "Erro";
                }

                foreach ($resultSelecionaPergunta as $valRSP) {
                    $perguntaValue = $valRSP['perguntaTxt'];
                    $perguntaIdValue = $valRSP['perguntaId'];
                }

                //Selecionando carta
                
                 $sqlSelecionaPergunta = "Select cartaID, cartaDirectory from sv_cartas WHERE cartaDesc = :cartaDesc";

                try{
                    $querySelecionaCarta = $conecta->prepare($sqlSelecionaPergunta);
                    $querySelecionaCarta->bindValue(":cartaDesc", $_POST['faseCarta'], PDO::PARAM_STR);
                    $querySelecionaCarta->execute();

                    $resultSelecionaCartaRow = $querySelecionaCarta->rowCount(PDO::FETCH_ASSOC);  

                    $resultSelecionaCarta = $querySelecionaCarta->fetchAll(PDO::FETCH_ASSOC);  

                }catch(PDOException $erroSelecionaModelo){
                    echo "<div class='alertaN'>Erro ao consultar modelo.</div>";
                }

                if ($resultSelecionaCartaRow == 0) {
                    echo "<div class='alertaN'>Carta inválida.</div>";
                    $cartaValue = "Erro";
                }

                foreach ($resultSelecionaCarta as $valCard) {
                    $cartaValue = $valCard['cartaDirectory'];
                    $cartaIdValue = $valCard['cartaID'];
                }

                
                ?>
                <h1>Criando Fases</h1>

                <form name="criarFase" method="POST" action="painel.php">

                    <input type="hidden" name="spv" value="create/fase3">

                     <div class="cardRight">
                        <?php 

                            if($resultSelecionaCartaRow != 0){
                                echo "<img src='../cartas/".$cartaValue."'>";
                            }else{
                                echo "";
                            }

                        ?>
                     </div>
                    
                    <label for="descricao">
                        <strong>Descrição:</strong>
                        <input type="text" value="<?php echo $_POST['faseDescricao']; ?>" name="faseDescricao"/>
                    </label>
                    <label for="carta">
                        <strong>Carta:</strong>
                        <input type="text" value='<?php echo $_POST['faseCarta']; ?>' name="faseCarta"/>
                        <input type="hidden" value='<?php echo $cartaIdValue; ?>' name="faseCarta2"/>
                    </label>
                    <label for="criterio">
                        <strong>Critério:</strong>
                    </label>

                    <select name="criterio">
                        <?php echo "<option value='".$_POST['criterio']."'>".$_POST['criterio']."</option>";?>
                    </select>

                    <label for="pontos">
                        <strong>Pontos:</strong>
                    </label>

                    <select name="fasePontos">
                        <?php echo "<option value='".$_POST['fasePontos']."'>".$_POST['fasePontos']."</option>";?>
                    </select>

                    <label for="perguntaNumber">
                        <strong>Nº:</strong>
                        <input type="text" value="<?php echo $_POST['fasePerguntaNumber'];?>" name="fasePerguntaNumber"/>
                    </label>

                    <label for"perguntaTxt">
                        <strong>Pergunta:</strong>
                        <input type="text" name="fasePerguntaTxt" value="<?php echo $perguntaValue;?>" disabled="disabled"/>
                    </label>

                    <label for="modelo">
                        <strong>Modelo:</strong>
                    </label>
                    
                    <select name="faseModelo">
                      <option value="<?php echo $modeloIdValue; ?>"><?php echo $modeloValue; ?></option>
                    </select>

                    <input name="fasePosicao" type="hidden" value="<?php echo $_POST['fasePosicao']; ?>" />

                    <input type="submit" name="btnCriaFase" value="Criar">

                </form>
            </div><!-- painel pagusers -->

                <?php
            }else{
                echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/fases'>";
                exit;
            }
        ?>
        
        

</div><!-- Content -->

<?php include_once 'footer.php'; ?>