<?php

if (isset($_POST['btnFiltar'])){
    
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

    <form name="filterCard" method="post" action="painel.php">

                <fieldset>

                    <input type='hidden' name='spv' value='search/filtreResult'/>
                    
                    <legend>Filtrar:</legend>
                    
                    <span>Sexo:</span>
                    <input type="radio" value="M" name="sexo"><strong> M </strong>
                    <input type="radio" value="F" name="sexo"><strong> F</strong>
                    
                    <strong style="padding-left: 5px">|</strong>
                    
                    <span>Idade:</span>
                    <input type="radio" value=">" name="faixa"><strong> Menor</strong>
                    <input type="radio" value="<" name="faixa"><strong> Maior</strong>
                    <input type="radio" value="=" name="faixa"><strong> Igual </strong>

                    <input name="faixaNumber" type="text" maxlength="2"/>
                    
                    <span>Modelo:</span>
                    <select name="modelo">
                      <option value="">Selecione</option>
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

                    $where = Array();

                    if(isset($_POST['sexo'])){ 
                      $where[] = " sv_users.userSexo = '".$_POST['sexo']."'"; 
                    }

                    if(isset($_POST['faixa']) and !empty($_POST['faixaNumber'])){

                      $hojeYear = date("Y", strtotime("today"));
                      $valorAno = $_POST['faixaNumber'];
                      $valorAno = $hojeYear-$valorAno;
                      $valorAno = (int)$valorAno;

                      $where[] = "CONVERT(RIGHT(sv_users.userDataNascimento, 4), SIGNED) ".$_POST['faixa']." $valorAno"; 

                    }

                    if(!empty($_POST['modelo'])){ $where[] = " sv_modelos.modelId = ".$_POST['modelo']; }
                    if(!empty($_POST['data'])){ $where[] = " sv_jogo.`Data` LIKE '".retornaData($_POST['data'])."%'"; }

                    //SQL para selecionar dados da tabela sv_jogo na base de dados
                    $sqlSJ = 'SELECT '. 
                             'sv_users.userID as `usuarioId`, '.
                             'sv_users.userNome as `nome`, '.
                             'sv_users.userSobrenome as `sobrenome`, '.
                             'sv_users.userDataNascimento as `idade`, '.
                             'sv_users.userSexo as `sexo`, '.
                             'sv_users.userEscolaridade as `escolaridade`, '.
                             'sv_modelos.modelNome as `modeloNome`, '.
                             'sv_jogo.Modelo, '.
                             'sv_jogo.`Data` as hora '.
                             'FROM sv_jogo '.
                             'INNER JOIN sv_users ON sv_jogo.UsuarioId = sv_users.userID '.
                             'INNER JOIN sv_modelos ON sv_jogo.Modelo = sv_modelos.modelId';

                        if( sizeof( $where ) )

                         $sqlSJ .= ' WHERE '.implode( ' AND ',$where );

                    try{

                        $querySJ=$conecta->prepare($sqlSJ);
                        $querySJ->execute();

                        $resultSJRow = $querySJ->rowCount(PDO::FETCH_ASSOC);
                        $resultSJ = $querySJ->fetchAll(PDO::FETCH_ASSOC);

                    }catch(PDOException $erroSJ){
                        echo "Erro ao pegar dados do jogo".$erroSJ->getMessage();
                    }

                    if($resultSJRow != 0){

                      foreach ($resultSJ as $valuesSJ) {
                          $usuarioId = $valuesSJ['usuarioId'];                        
                          $nome = $valuesSJ['nome'];
                          $sobrenome = $valuesSJ['sobrenome'];
                          $idade = $valuesSJ['idade'];
                          $sexo = $valuesSJ['sexo'];
                          $escolaridade = $valuesSJ['escolaridade'];
                          $nomeModelo = $valuesSJ['modeloNome'];
                          $hora = $valuesSJ['hora'];
                ?>
                <tr data-href="painel.php?spv=alter/usuarios&usr=<?php echo $usuarioId; ?>" >
                    <td style='text-align: left; padding-left: 5px;'><?php echo trim($nome)." ".trim($sobrenome);?></td>
                    <td><?php echo calcIdade($idade)." anos";?></td>
                    <td><?php echo $sexo;?></td>
                    <td style='text-align: left; padding: 5px;'><?php echo $escolaridade;?></td>
                    <td><?php echo $nomeModelo;?></td>
                    <td>
                        <?php 

                          /*
                            Calculado a quantidade de segundos que possui um dia. 
                            Chego nesse calculo pois uma dia é feito 24 horas 
                            e 24 horas vezes 3600 segudos que equivale a uma hora
                            em segundos chego no resultado esperado.

                            [86400] -> Um dia em segundos

                         */
                          $UmDiaEmSegundos = (24*3600);

                          //Pegando o valor timestamp da hora em que foi realizado o teste
                          $hora = date("Y-m-d", strtotime($valuesSJ['hora']));

                          //Usando a função 'strtotime()' para fazer a conversão em segundos
                          $hora = strtotime($hora);

                          //Verificando se a var '$hora' agora já em segundos é igual a hoje também em segundos
                          if($hora == strtotime("today")){

                            //realizado a impressão no seguinte formato 'Hoje às HH:m'
                            echo "Hoje às ".date("H:i", strtotime($valuesSJ['hora']));

                          }elseif ($hora == (strtotime("today")-$UmDiaEmSegundos)) {
                            
                              //realizado a impressão no seguinte formato 'Ontem às HH:m'
                              echo "Ontem às ".date("H:i", strtotime($valuesSJ['hora']));

                          }else{

                              //Não sendo igual a ontem e nem hoje em segundos imprimo a data e hora no formato local 'd/m/Y à H:m'
                              echo date("d/m/Y", strtotime($valuesSJ['hora']))." às ".date("H:i", strtotime($valuesSJ['hora']));  
                              
                          }

                        ?>
                    </td>
                </tr>
                <?php 

                    }

                }else{
                  echo "<tr><td colspan='6'>Nenhum resultado encontrado.</td></tr>";
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