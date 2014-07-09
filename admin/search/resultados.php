<?php 

if (isset($_GET['btnBuscaUSR']) and !empty($_GET['buscaUser'])) {
    
    //Armazeno nesta variável o que é passado por GET na URL              
    $buscaUsuario = $_GET['buscaUser'];
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>

<!-- 
    Função em JS para quando ocorrer o envento do usuário clicar em um linha 
    da tebela ser redirecioado para a pagina de resultados daquele respectivo 
    usuario representado na linha da tabela
-->

<script type="text/javascript" src="js/jquery-2.0.2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
        $('tr').click(function() {
        window.location.href = $(this).attr('data-href');
        });        
    });

</script>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/resultados">Resultados</a> &gt; Filtrar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagresultados">

        <?php 

            $sqlModel = "SELECT * FROM sv_modelos";

            try {

              $queryModel = $conecta->prepare($sqlModel);
              $queryModel->execute();

              $resultModel = $queryModel->fetchAll(PDO::FETCH_ASSOC);
              
            } catch (PDOException $erroModel) {
              echo "<div class='alertaN'>Erro ao selecionar modelos!</div>";
              $errado = 1;
            }
            
        ?>

         <form name="filterCard" method="get" action="painel.php">

            <input type="hidden" name="spv" value="search/buscaonecard" /> 

                <fieldset>
                    
                    <legend>Filtrar:</legend>
                    
                    <span>Sexo:</span>
                    <input type="radio" value="M" name="sexo"><strong> M </strong>
                    <input type="radio" value="F" name="sexo"><strong> F</strong>
                    
                    <strong style="padding-left: 5px">|</strong>
                    
                    <span>Idade:</span>
                    <input type="radio" value="<=" name="faixa"><strong> Menor</strong>
                    <input type="radio" value=">=" name="faixa"><strong> Maior</strong>
                    <input type="radio" checked="checked" value="==" name="faixa"><strong> Igual </strong>

                    <input name="faixaNumber" type="text" maxlength="2"/>
                    
                    <span>Modelo:</span>
                    <select name="cor">                        
                       <?php 

                            if (!isset($errado)) {
                              
                              foreach ($resultModel as $valModel) {
                                echo "<option value='".$valModel['modelId']."'>".ucwords($valModel['modelNome'])."</option>";
                              }

                            } else {
                              echo "<option value=''>Erro</option>";
                            }
                            
                       ?>
                    </select>

                    <span>Data:</span>
                    <input type='text' name='data' maxlength="10"/>

                    <input type="submit" name="btnFiltar" class="btnFiltar" value="Filtrar" />

                </fieldset>
            </form>

            <form name="userBusca" method="GET" action="painel.php">
                <fieldset>
                    <input type="hidden" name="spv" value="search/resultados"/>
                    <legend>Buscar Usuário</legend>
                    <input type="text" name="buscaUser"/>
                    <input type="submit" style="display: none" class="newbtn" name="btnBuscaUSR" value="Buscar">
                </fieldset>
            </form>

    <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th>Nome</th>
                <th>idade</th>
                <th>sexo</th>
                <th>escolaridade</th>
                <th>modelo</th>
                <th>Data do teste</th>
            </thead>
            <tbody style="background: #E7ECEF; text-align: center;">
                <?php 
                    
                        //SQL para selecionar usuarios buscados pelo formulário
                        $sqlSUC = "SELECT ".
                                    "sv_users.userID as `usuarioId`, ".
                                    "sv_users.userNome as `nome`, ".
                                    "sv_users.userSobrenome as `sobrenome`, ".
                                    "sv_users.userDataNascimento as `idade`, ".
                                    "sv_users.userSexo as `sexo`, ".
                                    "sv_users.userEscolaridade as `escolaridade`, ".
                                    "sv_modelos.modelNome as `modeloNome`, ".
                                    "sv_jogo.`Data` as `hora` ".
                                 "FROM sv_jogo ".
                                    "INNER JOIN sv_users ON sv_jogo.UsuarioId = sv_users.userID ".
                                    "INNER JOIN sv_modelos ON sv_users.userModelId = sv_modelos.modelId ".
                                "WHERE ".
                                        "MATCH(sv_users.userNome, sv_users.userSobrenome) AGAINST('".$buscaUsuario."') ".
                                "OR ".
                                        "((sv_users.userNome LIKE '%".$buscaUsuario."%') OR (sv_users.userSobrenome LIKE '%".$buscaUsuario."%'))";

                        try{
                            $querySUC = $conecta->prepare($sqlSUC);
                            $querySUC->execute();

                            //Contando a quantidade de registros encontrados
                            $rowresultSUC = $querySUC->rowCount(PDO::FETCH_ASSOC);

                            //Dando a informaçao de quando não obtiver nenhuma registro segundo o procurado
                            if ($rowresultSUC == 0) {
                                echo "<tr><td colspan='6' style='padding:5px;'>Nenhum resultados encontrado!</td></tr>";
                            }

                            //Guardando o valores retornados
                            $resultSUC = $querySUC->fetchAll(PDO::FETCH_ASSOC);

                        }catch(PDOException $erroSUC){
                            echo "Erro ao pesquisar usuario formulário 2";
                        }

                    //imprimindo os valores retornados na query acima
                        foreach ($resultSUC as $valSUC) {
                            $usuarioId = $valSUC['usuarioId'];
                            $nomeFist = trim($valSUC['nome']);
                            $nomeLast = trim($valSUC['sobrenome']);
                            $idadeInYears = calcIdade($valSUC['idade']);
                            $sexo = $valSUC['sexo'];
                            $escolaridade = $valSUC['escolaridade'];
                            $nomeOfModel = $valSUC['modeloNome'];
                            $dataHora = date("d/m/Y", strtotime($valSUC['hora']))." às ".date("H:i", strtotime($valSUC['hora']));
                        ?>
                        <tr data-href="painel.php?spv=alter/usuarios&usr=<?php echo $usuarioId; ?>" >
                            <td style='text-align: left; padding-left: 5px;'><?php echo $nomeFist." ".$nomeLast;?></td>
                            <td><?php echo $idadeInYears." anos";?></td>
                            <td><?php echo $sexo;?></td>
                            <td style='text-align: left; padding: 5px;'><?php echo $escolaridade;?></td>
                            <td><?php echo $nomeOfModel;?></td>
                            <td><?php echo $dataHora;?></td>
                        </tr>
                        <?php
                        }
                    
                ?>
        </tbody>
    </table>

    </div><!-- painel pagresultados -->

</div><!-- Content -->
            
<?php 

    include_once 'footer.php'; 
}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/resultados'>";
    exit;
}
?>