<?php 

//Verificando credenciais de login, verifica se login existe    
include_once 'system/restrito.php'; 

//Verifica a se existe a superglobal $_GET['regra']
if(isset($_GET['regra'])){

    //Inclui o arquivo de conexão com o BD
    include_once '../Connections/config.php';

    //Incluindo funções de formatação de  tempo
    include_once 'funcs/functions.php';

    //Incluindo cabeçalho do arquivo HTML
    include_once 'header.php';

    //Armazena variável passado por GET
    $regraID = $_GET['regra'];

    //Converte para inteiro
    $regraID = (int)$regraID; 

    //Selecionando dados da regra passada por GET
    $sqlReGET = "SELECT sv_regrasmovimento.*, sv_modelos.modelNome as modelo FROM sv_regrasmovimento LEFT JOIN sv_modelos ON sv_regrasmovimento.idModelo = sv_modelos.modelId WHERE IdRegra = :IdRegra";

    try{

        $queryReGET = $conecta->prepare($sqlReGET);
        $queryReGET->bindValue(":IdRegra", $regraID, PDO::PARAM_STR);
        $queryReGET->execute();

        $resultReGET = $queryReGET->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultReGET as $valReGET) {

            $regraIde       = $valReGET['IdRegra'];
            $regraPergunta  = $valReGET['IdPergunta'];
            $regraModeloId  = $valReGET['idModelo'];
            $regraModelo    = $valReGET['modelo'];
            $regraComeco    = convertTempo($valReGET['tempoInicial']);
            $regraFim       = convertTempo($valReGET['tempoFinal']);
            $regraCriterio  = $valReGET['posicao'];
            $regraPonto     = $valReGET['pontuacao'];

            if($regraModelo == null){
                $regraModelo = "Não definido";
            }

            $horaInicial = explode(":", $regraComeco);

            $horaFinal = explode(":", $regraFim);


        }

    }catch(PDOException $erroReGET){ 
        echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/regMove'>";
        exit;
    }

?>
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/regoptions'>Regras Extras</a> &gt; <a href='painel.php?spv=nav/regMove'>Regra de Movimentação</a> &gt; Alterar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">

        <?php 

            //Selecionando os modelos para exibir na sua respectiva label abaixo 
            $sqlModel = "SELECT * FROM sv_modelos ORDER BY sv_modelos.modelId DESC";

            try{

                $queryModel = $conecta->prepare($sqlModel);
                $queryModel->execute();

                $resultModel = $queryModel->fetchAll(PDO::FETCH_ASSOC);

            }catch(PDOException $erroModel){
                echo "<div class='alertaN'>Erro ao selecionar modelos para exibição.</div>";
            }

            //Inserindo as informações no banco se existir o submir do formulario
            if(isset($_POST['criaRegraTime'])){

                if (!empty($_POST['perguntaId']) and !empty($_POST['criterio'])) {
                    
                   $pergunta = trim($_POST['perguntaId']);
                   $modelo   = $_POST['modelo'];

                    //variáveis do tempo inicial
                    $inicioH = trim($_POST['tempoH']);
                    $inicioM = trim($_POST['tempoM']);
                    $inicioS = trim($_POST['tempoS']);

                    $inicio = convertTimeSencod($inicioH, $inicioM, $inicioS);

                    //variáveis do tempo final
                    $fimH = trim($_POST['finalH']);
                    $fimM = trim($_POST['finalM']);
                    $fimS = trim($_POST['finalS']);

                    $fim = convertTimeSencod($fimH, $fimM, $fimS);

                    $criterioMove = trim($_POST['criterio']);

                    $pontuacao = $_POST['pontos'];

                        $sqlReg = "UPDATE sv_regrasmovimento SET IdPergunta = :IdPergunta, idModelo = :idModelo, tempoInicial = :tempoInicial, tempoFinal = :tempoFinal, posicao = :posicao, pontuacao = :pontuacao WHERE IdRegra = $regraID";

                        try{

                            $queryReg = $conecta->prepare($sqlReg);
                            $queryReg->bindValue(":IdPergunta", $pergunta, PDO::PARAM_STR);
                            $queryReg->bindValue(":idModelo", $modelo, PDO::PARAM_STR);
                            $queryReg->bindValue(":tempoInicial", $inicio, PDO::PARAM_STR);
                            $queryReg->bindValue(":tempoFinal", $fim, PDO::PARAM_STR);
                            $queryReg->bindValue(":posicao", $criterioMove, PDO::PARAM_STR);
                            $queryReg->bindValue(":pontuacao", $pontuacao, PDO::PARAM_STR);
                            $queryReg->execute();

                            echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/regMove'>";
                            exit;
                        }catch(PDOException $erroReg){
                            echo "<div class='alertaN'>Erro ao atualizar informações da regra!</div>";
                        }

               }else{
                    echo "<div class='alertaN'>Algum campo em branco. Verifique e tente novamente!</div>";
               }

            }
        ?>

        <h1>Regra extra de movimento</h1>

        <form name="criaRegraTime" method="POST" action="">
            
            <label for="pergunta">
                <strong style="display:block;">Pergunta:</strong>
                <input style="width: 25px;  margin-top: 5px; text-align: center" value='<?php echo $regraPergunta; ?>'name="perguntaId" type="text">
            </label>

            <label for="lbModelo">
                <strong style="display:block;">Modelo:</strong>
            </label>

            <select name="modelo">
                
                <option value="<?php echo $regraModeloId; ?>"><?php echo $regraModelo; ?></option>

                <?php 

                    //Fazendo a exibição dos modelos
                    foreach ($resultModel as $valModel) {

                        $modeloId   = $valModel['modelId'];
                        $modeloNome = $valModel['modelNome'];

                        //Impedindo que seja impressos dois modelos de mesmo nome  
                        if($regraModeloId == $modeloId){
                            continue;
                        }else{
                            echo "<option value=".$modeloId.">".$modeloNome."</option>";    
                        }

                    }

                    if($regraModelo != null){
                        echo "<option value=''>Nenhum</option>";
                    }
                ?>

            </select>

            <label for="inicio">
                <strong style="margin: 5px 0; display:block;">Tempo Inicial:</strong>
                <strong style="margin: 0 0 0 5px; font-size: 12px;">H:</strong>
                <input style="width: 25px; text-align: center" name="tempoH" value='<?php echo $horaInicial[0]; ?>' type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">M:</strong>
                <input style="width: 25px; text-align: center" name="tempoM" value='<?php echo $horaInicial[1]; ?>' type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">S:</strong>
                <input style="width: 25px; text-align: center" name="tempoS" value='<?php echo $horaInicial[2]; ?>' type="text">
            </label>

            <label for="final">
                <strong style="margin: 5px 0; display:block;">Tempo Final:</strong>
                <strong style="margin: 0 0 0 5px; font-size: 12px;">H:</strong>
                <input style="width: 25px; text-align: center" name="finalH" value='<?php echo $horaFinal[0]; ?>' type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">M:</strong>
                <input style="width: 25px; text-align: center" name="finalM" value='<?php echo $horaFinal[1]; ?>' type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">S:</strong>
                <input style="width: 25px; text-align: center" name="finalS" value='<?php echo $horaFinal[2]; ?>' type="text">
            </label>

            <label for="lbCriterio">
                <strong style="display:block;">Critério:</strong>
            </label>

            <select name="criterio">
                <option value="<?php echo $regraCriterio;?>"><?php echo ucfirst($regraCriterio);?></option>
                <option value="ambos">Ambos</option>
                <option value="esquerda">Esquerda</option>
                <option value="direita">Direita</option>
            </select>

            <label for="ponto">
                <strong>Pontos:</strong>
            </label>

            <select name="pontos">
                <?php echo "<option value=".$regraPonto.">".$regraPonto."</option>"; ?>
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

            <input type="submit" class="newbtn" name="criaRegraTime" value="Salvar">

        </form>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php 
    
    include_once 'footer.php'; 

}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/regTime'>";
    exit;
}
?>   