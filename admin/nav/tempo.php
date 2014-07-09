<?php

    include_once 'system/restrito.php'; 
    include_once "funcs/functions.php";
    include_once '../Connections/config.php';
    include_once 'header.php';
    
?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Tempo
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagtempo">
        
        <!--<label class="searchUsers">
            <span>Pesquise: </span>
            <input type="text" name="searchUsers" />
            <input type="submit" name="btnSearch" class="btnSearch" value="Buscar" />            
        </label>-->
        
        <table width="100%" border="0" cellspacing="2" cellpadding="0" style="float: left;">
            <thead style="background: #70909D; color:#fff; font: 12px Arial, Helvetica, sans-serif; font-weight:bold; text-transform: uppercase;">
                <th> <img src="images/seta_bottom.png" height="10px"/></th>
                <th>ordem</th>
                <th>tempo</th>        
                <th>visibilidade inicial</th>        
                <th>mudando em</th>
                <th style="padding: 0 5px">Alterar</th>
                <th style="padding: 0 5px">Excluir</th>
            </thead>
            <tbody style="background: #E7ECEF; text-align: center;">
                <?php                
    
                    $sql_selecionaTempo = "SELECT * FROM sv_tempo";

                    try {

                        $query_selecionaTempo = $conecta->prepare($sql_selecionaTempo);
                        $query_selecionaTempo->execute();

                        $result_selecionaTempo = $query_selecionaTempo->fetchAll(PDO::FETCH_ASSOC);
                        $rowCount_selecionaTempo = $query_selecionaTempo->rowCount(PDO::FETCH_ASSOC);

                    } catch (PDOException $erro_selecionaTempo) {
                        echo "<div class='erro'>Erro ao selecionar todas instruções</div>";
                    }

                    foreach ($result_selecionaTempo as $res_selecionaTempo){
                        $tempoId = $res_selecionaTempo['tempoId'];
                        $tempoOrdem = $res_selecionaTempo['tempoOrdem'];  
                        $tempoTotal = $res_selecionaTempo['tempoTotal'];
                        $tempoVisao = $res_selecionaTempo['tempoVisao'];
                        $tempoMomento = $res_selecionaTempo['tempoMomento'];

                        if ($tempoVisao == 'normal') {
                            $tempoVisao = "Visível";
                        }elseif ($tempoVisao == 'invisivel') {
                            $tempoVisao = 'Invisível';
                        }else{
                            $tempoVisao = 'Erro';
                        }
                                                                      
                ?>
                <tr>
                    <td><?php echo $tempoId;?></td>
                    <td><?php echo $tempoOrdem;?></td>
                    <td><?php echo convertTempo($tempoTotal);?></td>
                    <td><?php echo $tempoVisao;?></td>
                    <td><?php if ($tempoMomento == null) { echo "---------------"; }else{ echo $tempoMomento; }?></td>
                    <td class="acao"><a href="painel.php?spv=alter/tempo&alt=<?php echo $tempoId;?>"><img src="images/icons/edit_3.png"/></a></td>
                    <td class="acao"><a href="painel.php?spv=drop/tempo&dp=<?php echo $tempoId;?>"><img src="images/icons/del_5.png"/></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <a class="btnNewFase" href="painel.php?spv=create/tempo"> Novo Tempo</a>
        
    </div><!-- painel pagfases -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>