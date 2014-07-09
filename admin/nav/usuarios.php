<?php 

    include_once 'system/restrito.php';
    include_once 'header.php'; 
    include_once '../Connections/config.php'; 
?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Usuários
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagusers">

        <script type="text/javascript" src="js/jquery-2.0.2.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function (){
                $('tr').click(function() {
                window.location.href = $(this).attr('data-href');
                });        
            });
        </script>

        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th>Nome</th>
                <th>Inscrição</th>
                <th>Status</th>
                <th>Acesso</th>
                <th style="padding: 0 5px;">Excluir</th>
            </thead>
            <tbody style="background: #E7ECEF; text-align: center;">
                <?php 
                    
                    $sql_selecionaAllUser = "SELECT * FROM sv_users ORDER BY sv_users.userIncritoEm DESC";
                    
                    try {
                        
                        $query_selecionaAllUser = $conecta->prepare($sql_selecionaAllUser);
                        $query_selecionaAllUser->execute();
                        
                        $res_selecionaAllUsers = $query_selecionaAllUser->fetchAll(PDO::FETCH_ASSOC);
                        
                    } catch (PDOException $erro_allUser) {
                        echo "erro ao selecionar os usuarios";
                    }
                    
                    foreach ($res_selecionaAllUsers as $results){
                    
                    $userID        = $results['userID'];
                    $userIncritoEm = $results['userIncritoEm'];                  
                    $userNome      = trim($results['userNome']);
                    $userSobrenome = trim($results['userSobrenome']);
                    $userEmail     = $results['userEmail'];                
                    $userStatus    = $results['userStatus'];
                    $userNivel    = $results['userNivel'];

                    if($userNivel == "sim"){
                        $userNivel = "Habilitado";
                    }else{
                        $userNivel = "Desabilitado";
                    }

                ?>
                <tr data-href="painel.php?spv=alter/usuarios&usr=<?php echo $userID; ?>">
                    <td style='text-align: left; padding-left: 5px;'><?php echo ucwords($userNome)." ".ucwords($userSobrenome);?></td>
                    <td style="width: 140px;">
                        <?php 

                          $UmDiaEmSegundos = (24*3600);
                          $diaInscricao = date("Y-m-d", strtotime($userIncritoEm));
                          $diaInscricao = strtotime($diaInscricao);

                          if($diaInscricao == strtotime("today")){
                            echo "Hoje às ".date("H:i", strtotime($userIncritoEm));
                          }elseif ($diaInscricao == (strtotime("today")-$UmDiaEmSegundos)) {
                              echo "Ontem às ".date("H:i", strtotime($userIncritoEm));
                          }else{
                              echo date("d/m/Y", strtotime($userIncritoEm))." às ".date("H:i", strtotime($userIncritoEm));  
                          }
                          

                        ?>
                    </td>                                        
                    <td style="width: 90px;"><?php echo $userStatus;?></td>
                    <td style="width: 120px;"><?php echo $userNivel;?></td>
                    <td class="acao"><a href="painel.php?spv=drop/usuarios&id=<?php echo $userID; ?>"><img src="images/icons/delUser.png"/></a></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        
        <div style="clear: both"></div>
                    
            <?php
                
                if ($admEmail == "super") {
                    echo "<br/><a class='btnNewFase' style='width: 120px; margin: -15px 383px 0 0;' href='painel.php?spv=nav/administrators'>Administradores</a>";
                }
            
            ?>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>