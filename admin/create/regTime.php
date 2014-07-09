<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/regoptions'>Regras Extras</a> &gt; <a href='painel.php?spv=nav/regTime'>Regra de Tempo</a> &gt; Criar
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

                if (!empty($_POST['perguntaId']) and !empty($_POST['modelo']) and !empty($_POST['cicloTime'])) {
                    
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

                    $repeticao = trim($_POST['cicloTime']);

                    $pontuacao = $_POST['pontos'];

                        $sqlReg = "INSERT INTO sv_regrastempo (IdPergunta, idModelo, tempoInicial, tempoFinal, cicloRepeticao, pontuacao) ".
                                                       "VALUES(:IdPergunta, :idModelo, :tempoInicial, :tempoFinal, :cicloRepeticao, :pontuacao)";

                        try{

                            $queryReg = $conecta->prepare($sqlReg);
                            $queryReg->bindValue(":IdPergunta", $pergunta, PDO::PARAM_STR);
                            $queryReg->bindValue(":idModelo", $modelo, PDO::PARAM_STR);
                            $queryReg->bindValue(":tempoInicial", $inicio, PDO::PARAM_STR);
                            $queryReg->bindValue(":tempoFinal", $fim, PDO::PARAM_STR);
                            $queryReg->bindValue(":cicloRepeticao", $repeticao, PDO::PARAM_STR);
                            $queryReg->bindValue(":pontuacao", $pontuacao, PDO::PARAM_STR);
                            $queryReg->execute();

                            echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/regTime'>";
                            exit;

                        }catch(PDOException $erroReg){
                            echo "<div class='alertaN'>Erro ao gravar informações da regra!</div>";
                        }

               }else{
                    echo "<div class='alertaN'>Algum campo em branco. Verifique e tente novamente!</div>";
               }

            }
        ?>

        <h1>Regra extra de tempo</h1>

        <form name="criaRegraTime" method="POST" action="">
            
            <label for="pergunta">
                <strong style="display:block;">Pergunta:</strong>
                <input style="width: 25px;  margin-top: 5px; text-align: center" name="perguntaId" type="text">
            </label>

            <label for="lbModelo">
                <strong style="display:block;">Modelo:</strong>
            </label>

            <select name="modelo">
                
                <option value="">Selecione</option>

                <?php 

                    //Fazendo a exibição dos modelos
                    foreach ($resultModel as $valModel) {

                        $modeloId   = $valModel['modelId'];
                        $modeloNome = $valModel['modelNome'];

                        echo "<option value=".$modeloId.">".$modeloNome."</option>";

                    }
                ?>

            </select>

            <label for="inicio">
                <strong style="margin: 5px 0; display:block;">Tempo Inicial:</strong>
                <strong style="margin: 0 0 0 5px; font-size: 12px;">H:</strong>
                <input style="width: 25px; text-align: center" name="tempoH" type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">M:</strong>
                <input style="width: 25px; text-align: center" name="tempoM" type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">S:</strong>
                <input style="width: 25px; text-align: center" name="tempoS" type="text">
            </label>

            <label for="final">
                <strong style="margin: 5px 0; display:block;">Tempo Final:</strong>
                <strong style="margin: 0 0 0 5px; font-size: 12px;">H:</strong>
                <input style="width: 25px; text-align: center" name="finalH" type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">M:</strong>
                <input style="width: 25px; text-align: center" name="finalM" type="text">
                <strong style="margin: 0 0 0 5px; font-size: 12px;">S:</strong>
                <input style="width: 25px; text-align: center" name="finalS" type="text">
            </label>

            <label for="ciclo">
                <strong style="display:block;">Ciclo:</strong>
                <input style="width: 25px;  margin-top: 5px; text-align: center" name="cicloTime" type="text"> 
            </label>

            <label for="ponto">
                <strong>Pontos:</strong>
            </label>

            <select name="pontos">
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

            <input type="submit" class="newbtn" name="criaRegraTime" value="Criar">

        </form>

    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>   