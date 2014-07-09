<?php 

if(isset($_GET['usr']) and !empty($_GET['usr'])){

    include_once 'system/restrito.php';
    include_once 'header.php'; 
    include_once 'funcs/functions.php';
    include_once '../Connections/config.php'; 

?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários</a>
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagusers">

        <?php

            /*
              Código responsável por alteração na tabela usuario:
              -Altera modelo 
              -Libera acesso
            */

            if(isset($_POST['btnAlterUser'])){

                if(!empty($_POST['modelo'])){

                    $upModelId   = $_POST['modelo'];
                    $upUserNivel = $_POST['acesso'];
                    $upUserId    = $_GET['usr'];

                    $sqlUpdate = "UPDATE sv_users SET userModelId = :userModelId, userNivel = :userNivel WHERE userID = :userID";

                    try {

                        $queryUpdate = $conecta->prepare($sqlUpdate);
                        $queryUpdate->bindValue(":userModelId", $upModelId, PDO::PARAM_STR);
                        $queryUpdate->bindValue(":userNivel", $upUserNivel, PDO::PARAM_STR);
                        $queryUpdate->bindValue(":userID", $upUserId, PDO::PARAM_STR);
                        $queryUpdate->execute();

                    } catch (PDOException $erroUpdate) {
                        echo "<div style='margin-bottom: 10px;' class='alertaN'>Erro ao alterar dados do usuario</div>";
                    }

                }else{
                    echo "<div style='margin-bottom: 10px;' class='alertaN'>Defina um modelo</div>";
                }
                
            }
        ?>

        <form name="infoUser" method="POST" class="formUser" action="">

            <?php

                $sqlSelectOpcional = "SELECT ".
                                        "sv_users.*, ".
                                        "sv_modelos.modelNome as userModeloNome ".
                                     "FROM sv_users ".
                                     "LEFT JOIN sv_modelos ON sv_users.userModelId = sv_modelos.modelId ".
                                     "WHERE userID = :userID";

                try{

                    $queryVerifica = $conecta->prepare($sqlSelectOpcional);
                    $queryVerifica->bindValue(":userID", $_GET['usr'], PDO::PARAM_STR);
                    $queryVerifica->execute();

                    $resultVerifica = $queryVerifica->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resultVerifica as $value) {

                                $nome         = ucfirst($value['userNome']);
                                $sobrenome    = ucwords($value['userSobrenome']);
                                $sexo         = $value['userSexo'];
                                $idade        = $value['userDataNascimento'];
                                $escolaridade = $value['userEscolaridade'];
                                $inscricao    = $value['userIncritoEm'];
                                $email        = $value['userEmail'];
                                $acesso       = $value['userNivel'];
                                $status       = ucfirst($value['userStatus']);
                                $IdModelo     = $value['userModelId'];
                                $NomeModelo   = $value['userModeloNome'];

                    }

                }catch(PDOException $erroSeleciona){
                    echo "Erro ao selecionar dados do usuario ";
                }
                
            ?>

                <label>
                    <strong>Nome: </strong>
                    <span><?php echo $nome." ".$sobrenome;?></span>
                </label>

                <label>
                    <strong>Sexo: </strong>
                    <span><?php if($sexo == "M"){ echo "Masculino"; }elseif($sexo == "F"){ echo "Femenino";} ?></span>
                </label>

                <label>
                    <strong>Idade: </strong>
                    <span><?php echo calcIdade($idade); ?></span>
                </label>

                <label>
                    <strong>Escolaridade: </strong>
                    <span><?php echo $escolaridade; ?></span>
                </label>

                <label>
                    <strong>Inscrito Em: </strong>
                    <span><?php echo date("d/m/Y", strtotime($inscricao))." às ".date("H:i:s", strtotime($inscricao));?></span>
                </label>

                <label>
                    <strong>E-mail: </strong>
                    <span><?php echo $email; ?></span>
                </label>

                <label>
                    <strong>Modelo:</strong>                    
                </label>

                <select name="modelo">
                    <?php

                        if(empty($IdModelo)){
                            echo "<option value=''>Sem Modelo</option>";
                        }

                        $sqlModel = "SELECT * FROM sv_modelos";

                        try {

                            $queryModel=$conecta->prepare($sqlModel);
                            $queryModel->execute();

                            $resultModel = $queryModel->fetchAll(PDO::FETCH_ASSOC);

                            //Esse aqui é modelo atual do user
                            echo "<option value='".$IdModelo."'>".ucwords($NomeModelo)."</option>";

                            foreach ($resultModel as $valModel) {
                                if ($valModel['modelId'] == $IdModelo) {
                                     continue;
                                } else {
                                    echo "<option value='".$valModel['modelId']."'>".ucwords($valModel['modelNome'])."</option>";    
                                }
                            }

                        } catch (PDOException $erroModel) {
                            echo "<option value=''>Erro</option>";
                        }

                    ?>
                </select>

                <label>
                    <strong>Acesso:</strong>
                </label>

                <select name="acesso">
                    <?php 
                        if($acesso == "sim"){
                            echo "<option value='sim'>Habilitado</option>";
                            echo "<option value='nao'>Desabilitado</option>";
                        }elseif ($acesso = "nao") {
                            echo "<option value='nao'>Desabilitado</option>";
                            echo "<option value='sim'>Habilitado</option>";
                        }
                    ?>
                </select>

                <?php //} ?>

                <input class="newbtn" type="submit" name="btnAlterUser" value="Salvar">

        </form>

        <?php 

            $sqlVerificaJogo = "SELECT * FROM sv_jogo WHERE UsuarioId = :UsuarioId";

            try{

                $queryVJ = $conecta->prepare($sqlVerificaJogo);
                $queryVJ->bindValue(":UsuarioId", $_GET['usr'], PDO::PARAM_STR);
                $queryVJ->execute();

               $rowVJ = $queryVJ->rowCount(PDO::FETCH_ASSOC);

               if($rowVJ != 0){
                    $resVJ = $queryVJ->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resVJ as $keys) {
                        $horaExame = $keys['Data'];
                        $idJogo = $keys['JogoId'];
                    }

               }
            }catch(PDOException $erroVJogo){
                echo "Erro ao seleciona jogo";
            }
        ?>

        <span style="float: right; display: block; font: 11px Arial, Heveltica, sans-serif; 
                     color: #555; text-shadow: 0 0 0.3px #333">
                     Data do Teste: 
                     <?php 

                        if($rowVJ != 0){
                            echo date("d/m/Y", strtotime($horaExame))." às ".date("H:i", strtotime($horaExame));
                        }else{
                            echo "<b>Teste não realizado.</b>";
                        }

                    ?></span>

        <h1 style="width: 880px; margin-left: -5px;">Relatório Fases: </h1>

        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th>Fase</th>
                <th>Carta</th>
                <th>Resposta</th>
                <th>Tempo</th>
                <th>Pontos</th>
            </thead>
            <tbody style="background: #E7ECEF; text-align: center;">
                <?php 
                    if($rowVJ == 0){
                        echo "<tr>".
                                "<td colspan='6' style='color: #fff; text-shadow: 0 0 2px #333; background: #29346f; padding: 5px 0;'>Teste ainda não realizado!</td>".
                             "</tr>";
                    }else{

                        $sqlSRelatorio = "SELECT * FROM sv_relatoriofase WHERE rFaseUsuarioId = :rFaseUsuarioId";

                        try{

                            $querySRelatorio=$conecta->prepare($sqlSRelatorio);
                            $querySRelatorio->bindValue(":rFaseUsuarioId", $_GET['usr'], PDO::PARAM_STR);
                            $querySRelatorio->execute();

                            $rowResultSR = $querySRelatorio->rowCount(PDO::FETCH_ASSOC);

                        }catch(PDOException $erroSRelatorio){
                            echo "Erro ao selecioar relatórios do usuario";
                        }

                        if($rowResultSR > 0){

                            try{

                                $resultSR = $querySRelatorio->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($resultSR as $valueSR) {

                                    $fase     = $valueSR['rFaseFaseID'];
                                    $carta    = $valueSR['rFaseCartaAtual'];
                                    $respostaT = $valueSR['FaseResposta'];
                                    $tempo    = $valueSR['rFaseTempoGasto'];
                                    $ponto    = $valueSR['rFasePontoGanho'];

                                    if (empty($respostaT) and $ponto == 0) {
                                              $respostaT= "Não pontuou!";
                                    }

                                    echo "<tr>".
                                            "<td>$fase</td>".
                                            "<td><img width='100px' src='../cartas/".$carta.".png'/></td>".                   
                                            "<td>$respostaT</td>".
                                            "<td>$tempo</td>".
                                            "<td>$ponto</td>".
                                         "</tr>";
                                    
                                }

                            }catch(PDOException $erroPegaRelatorio){
                                echo "Erro ao pegar relatório";
                            }

                        }else{

                            $sqlCancel = "SELECT * from sv_cancel WHERE cancelUser = :cancelUser";

                            try{
                                $queryCancel=$conecta->prepare($sqlCancel);
                                $queryCancel->bindValue(":cancelUser", $_GET['usr'], PDO::PARAM_STR);
                                $queryCancel->execute();

                                $resulRowCancel = $queryCancel->rowCount(PDO::FETCH_ASSOC);

                                if ($resulRowCancel >= 1){
                                    echo "<td colspan='7' style='color: #333; text-shadow: 0 0 1px #fff; background: #FF7979; padding: 5px 0;'>Usuário cancelou teste.</td></td>";
                                }else{
                                    echo "<td colspan='7' style='color: #fff; text-shadow: 0 0 2px #333; background: #29346f; padding: 5px 0;'>Teste ainda não realizado.</td>";
                                }
                            }catch(PDOException $erroCancel){
                                echo "Erro ao selecionar cancelamento";
                            }

                        }
                    }
                ?>
            </tbody>
        </table>

        <h1 style="width: 880px; margin-left: -5px;">Relatório Regras Extras:</h1>

        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th>Regra</th>
                <th>Carta</th>
                <th>Critério</th>
                <th>Resposta</th>
                <th>Tempo</th>
                <th>Pontos</th>
            </thead>
            <tbody style="background: #E7ECEF; text-align: center;">
                  <?php 
                    if($rowVJ == 0){
                        echo "<tr>".
                                "<td colspan='7' style='color: #fff; text-shadow: 0 0 2px #333; background: #29346f; padding: 5px 0;'>Teste ainda não realizado!</td>".
                             "</tr>";
                    }else{

                        try{
                            //Verificando a existência de regra extra de tempo e movimentação

                            //SQL para regra de tempo
                            $sqlRET = "SELECT * FROM sv_regrastempo WHERE IdModelo = :IdModelo";

                            //SQL para regra de movimentação das cartas
                            $sqlREM = "SELECT * FROM sv_regrasmovimento WHERE IdModelo = :IdModelo";

                            //Query abaixo para a SQL de tempo
                            $queryRET = $conecta->prepare($sqlRET);
                            $queryRET->bindValue(":IdModelo", $IdModelo, PDO::PARAM_STR);
                            $queryRET->execute();

                            //Query abaixo para a SQL de movimentação
                            $queryREM = $conecta->prepare($sqlREM);
                            $queryREM->bindValue(":IdModelo", $IdModelo, PDO::PARAM_STR);
                            $queryREM->execute();

                            //Cotando quantos registros foram encontrados seguindo a sua respectiva passa acima
                            $resultRET = $queryRET->rowCount(PDO::FETCH_ASSOC);

                            //Cotando quantos registros foram encontrados seguindo a sua respectiva passa acima
                            $resultREM = $queryREM->rowCount(PDO::FETCH_ASSOC);

                        }catch(PDOException $erroVERT){
                            echo "Erro ao verficar existenia de regra extra de tempo";
                        }

                        if($resultREM != 0 or $resultRET != 0){
                            //Se existe alguma regra ele vai buscar seu relatórios

                            //SQL para selecionar relatórios extras de tempo e modelo
                            $sqlSREMT = "SELECT * FROM `sv_relatorioextra` WHERE idJogo = :idJogo";

                            try{

                                //Query para selecionar e retorna os dados o relatório
                                $querySREMT = $conecta->prepare($sqlSREMT);
                                $querySREMT->bindValue(":idJogo", $idJogo, PDO::PARAM_STR);
                                $querySREMT->execute();

                                $resulSREMT = $querySREMT->fetchAll(PDO::FETCH_ASSOC);
                                $rowResulSREMT = $querySREMT->rowCount(PDO::FETCH_ASSOC);

                                /*
                                  Caso não der tempo dá regra extra entrar ou outro motivo extraordinario 
                                  e a regra chegar a não ser utilizada irá exibir a mensagem abaixo 
                                */
                                if($rowResulSREMT == 0){

                                    echo "<tr>".
                                            "<td colspan='7' style='color: #fff; text-shadow: 0 0 2px #333; background: #29346f; padding: 5px 0;'> Regra não utilizada durante o teste!</td>".
                                         "</tr>";

                                }

                                //quando usada continua o fluxo do codigo

                                foreach ($resulSREMT as $valSREMT) {
                                    
                                }


                            }catch(PDOException $erroSREMT){
                                echo "Erro ao selecionar dados do relatório de regra extra";
                            }

                            //percorrendo os dados e os imprimindo
                            foreach ($resulSREMT as $valueSREMT) {
                                
                            }

                        }elseif($resultREM == 0 and $resultRET == 0){
                            //Se não há nenhuma regra atribuida ao sistema ele imprime essa mensagem na tela
                            echo "<tr>".
                                    "<td colspan='7' style='color: #fff; text-shadow: 0 0 2px #333; background: #29346f; padding: 5px 0;'>Nenhuma regra atribuida à este modelo!</td>".
                                "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php 

include_once 'footer.php'; 

}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/usuarios'>";
    exit;
}
?>