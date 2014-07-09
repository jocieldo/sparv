<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php'; 
    include_once 'header.php'; 

?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Modelo
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">

        <?php 

            $sql = "SELECT ".
                        "sv_users.userNome as nome, ".
                        "sv_users.userSobrenome as sobrenome, ".
                        "sv_modelos.modelNome as modelo, ".
                        "sv_cancel.cancelData as hora ".
                    "FROM sv_cancel ".
                    "INNER JOIN sv_users ON sv_cancel.cancelUser = sv_users.userID ".
                    "INNER JOIN sv_modelos ON sv_cancel.cancelModel = sv_modelos.modelId";

            try{

                $query = $conecta->prepare($sql);
                $query->execute();

                $resultRow = $query->rowCount(PDO::FETCH_ASSOC);
                $result = $query->fetchAll(PDO::FETCH_ASSOC);

            }catch(PDOException $erro){
                echo "<div class='alertaN'>Erro ao selecionar cancelamentos de testes.</div>";                
            }
        ?>

        <h1>Testes cancelados</h1>

        <table width='100%' border='0' cellspacing='2' cellpadding='0' style='margin-bottom: 10px; float: left;'>
            <tbody style='background: #E7ECEF;'>
                <tr>
                    <th class="titleModel">USUÁRIO</th>
                    <th class="titleModel">MODELO</th>
                    <th class="titleModel">DATA</th>
                </tr>
                <?php 

                    if($resultRow != 0){

                        foreach ($result as $values) {
                            echo "<tr style='text-align: center; padding: 3px;'>
                                    <td style='padding: 5px'>".ucfirst($values['nome'])." ".ucwords($values['sobrenome'])."</td>
                                    <td>".ucwords($values['modelo'])."</td>
                                    <td>".date("d-m-Y H:i:s", strtotime($values['hora']))."</td>
                                </tr>";
                        }
                        
                    }else{
                        echo "<tr><td colspan='3' style='padding: 5px; text-align: center; color: #fff; background: #bf3030;'>Nenhum cancelamento registrado.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div><!-- painel table -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>           