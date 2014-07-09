<?php 
include_once 'system/restrito.php';
include_once 'header.php';
include_once "../Connections/config.php";
?>
                
<script type="text/javascript">
    $(document).ready(function (){
        $('tr').click(function() {
            window.location.href = $(this).attr('data-href');
        });        
    });
</script>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários</a> &gt; Administradores (*somente o super)
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class='pagmodelos'>

        <?php  

            $sql_AllAdm = "SELECT * FROM sv_adm ORDER BY admID DESC";
                    
            try {
                
                $query_AllAdm = $conecta->prepare($sql_AllAdm);
                $query_AllAdm->execute();
                
                $resultado_AllAdm = $query_AllAdm->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (PDOException $erro_AllAdm) {
                echo "<div class='alertaN'>Não foi possível selecionar todos os administradores!</div>";
            }

        ?>

        <h1>Administradores</h1>

        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="text-align: center; background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th>Nome</th>
                <th>E-mail</th>
                <th>Acesso</th>
                <th colspan='2'>Acesso</th>
            </thead>
            <tbody style="background: #E7ECEF; color: #444;">
                <?php
                    
                    foreach ($resultado_AllAdm as $AllAdms){
                    
                    $idAdm = $AllAdms['admID'];
                    $nomeAdm = $AllAdms['admNome']." ".$AllAdms['admSobrenome'];
                    $emailAdm = $AllAdms['admEmail'];
                    $acessoAdm = $AllAdms['admAccess'];

                    if ($emailAdm == 'super') {

                        //ocultando o usuario super da listagem
                        continue;
                        
                    }
                    
                ?>
                <tr data-href="painel.php?spv=alter/passAdmin&admin=<?php echo $idAdm;?>" style="padding: 3px 0">
                    <td style="padding: 0 3px;"><?php echo ucwords($nomeAdm);?></td>
                    <td  style="padding: 0 5px; margin: 0 5px;"><?php echo $emailAdm;?></td>
                    <td style="text-align: center; padding: 0 3px;">
                        <?php 
                            if($acessoAdm == "yes"){
                                echo "Habilitado";
                            }else{
                                echo "Desabilitado";
                            }
                        ?>
                    </td>
                    <td class="acao">
                        <a href="painel.php?spv=alter/administrators&admin=<?php echo $idAdm;?>">
                            <img src="images/icons/edit_3.png"/>
                        </a>
                    </td>
                    <td class="acao">
                        <a href="painel.php?spv=drop/administrators&admin=<?php echo $idAdm;?>">
                            <img src="images/icons/del_5.png"/>
                        </a>
                    </td>
                </tr>
                    <?php } ?>
            </tbody>
        </table>

        <a class="btnNewFase" href="painel.php?spv=create/administrators">Novo Admin</a>
    
    </div><!-- PagModelos -->

</div><!-- Content -->

<?php include_once "footer.php"; ?>