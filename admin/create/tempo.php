<?php 
include_once 'system/restrito.php';
include_once '../Connections/config.php';
include_once 'funcs/functions.php';
include_once 'header.php';
?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/tempo">Tempo</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagusers">

        <?php 

            if (isset($_POST['btnCriaTempo'])){
                
                $ordem         = trim($_POST['ordem']);

                $timeHours     = trim($_POST['tempoHora']);
                $timeMinutes   = trim($_POST['tempoMinuto']);
                $timeSeconds   = trim($_POST['tempoSegundo']);

                $time = convertTimeSencod($timeHours, $timeMinutes, $timeSeconds);

                $visao         = trim($_POST['visibilidade']);

                $momentHours   = trim($_POST['momentoHora']);
                $momentMinutes = trim($_POST['momentoMinuto']);
                $momentSeconds = trim($_POST['momentoSegundo']);

                $moment = $momentHours.":".$momentMinutes.":".$momentSeconds;

                if(!empty($time) and !empty($visao) and !empty($moment) and !empty($ordem)){
                    
                    $sqlCriaTempo = "INSERT INTO sv_tempo (tempoTotal, tempoVisao, tempoMomento, tempoOrdem)"
                                   ." values (:tempoTotal, :tempoVisao, :tempoMomento, :tempoOrdem)";

                    $queryCriaTempo = $conecta->prepare($sqlCriaTempo);                                   
                    $queryCriaTempo->bindValue(":tempoTotal", $time, PDO::PARAM_STR);
                    $queryCriaTempo->bindValue(":tempoVisao", $visao, PDO::PARAM_STR);
                    $queryCriaTempo->bindValue(":tempoMomento", $moment, PDO::PARAM_STR);
                    $queryCriaTempo->bindValue(":tempoOrdem", $ordem, PDO::PARAM_STR);
                    $queryCriaTempo->execute();

                    echo "<div class='alertaP'> Definição <strong>tempo</strong> para <strong>modelo</strong> criado com sucesso. </div>";
                    echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/tempo'>";

                }


            }

        ?>
        
        <h1>Criando Tempo</h1>        

        <form id="criandoTempo" name="criaTempo" method="post" action="">

            <label>
                <strong>Ordem: </strong>
            </label>
                <select name="ordem">
                    <option value="Crescente">Crescente</option>
                    <option value="Decrescente">Decrescente</option>
                </select>
            


            <label>
                <strong>Tempo: </strong>
            </label>
                <input class="time" type="text" name="tempoHora" class="time" value="00" size="1"/>
                <input type="text" name="tempoMinuto" class="time" value="00" size="1"/>
                <input type="text" name="tempoSegundo" class="time" value="00" size="1"/>
                <em>Ex: HH:mm:ss</em>
            

            <label>
                <strong>Visibilidade Inicial: </strong>
            </label>
                <select name="visibilidade">
                    <option value="normal" >Visível</option>
                    <option value="invisivel" >Invisível</option>
                </select>
            

            <label>
                <strong>Mudando em: </strong>
            </label>
                <input class="time" type="text" name="momentoHora" class="time" size="1" value="00" />
                <input type="text" name="momentoMinuto" class="time" size="1" value="00" />
                <input type="text" name="momentoSegundo" class="time" size="1" value="00" />
                <em>Ex: HH:mm:ss</em>
            

            <input type="submit" name="btnCriaTempo" class="newbtn" value="Criar"/>

        </form>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>