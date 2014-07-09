<?php 

include_once 'system/restrito.php';
include_once 'funcs/functions.php';
include_once 'header.php';

if (isset($_GET['alt']) and !empty($_GET['alt'])) {

    include_once "../Connections/config.php";
    
    $alterar = $_GET['alt'];

?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/tempo'>Tempo</a> &gt; Alterar 
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagusers">

        <?php 

                if (isset($_POST['btnAlterTempo'])) {

                $ordem         = trim($_POST['ordem']);
                $timeHours     = trim($_POST['tempoHora']);
                $timeMinutes   = trim($_POST['tempoMinuto']);
                $timeSeconds   = trim($_POST['tempoSegundo']);

                $time = convertTimeSencod($timeHours, $timeMinutes, $timeSeconds);
                
                $visao         = trim($_POST['visibilidade']);

                $momentHours   = trim($_POST['momentoHora']);
                $momentMinutes = trim($_POST['momentoMinuto']);
                $momentSeconds = trim($_POST['momentoSegundo']);



                if ($momentHours < 10 && $momentHours > 0 && strlen($momentHours) == 1) {
                    $momentHours = "0".$momentHours;
                }

                if ($momentMinutes < 10 && $momentMinutes > 0 && strlen($momentMinutes) == 1) {
                    $momentMinutes = "0".$momentMinutes;
                }

                if ($momentSeconds < 10 && $momentSeconds > 0 && strlen($momentSeconds) == 1 ) {
                    $momentSeconds = "0".$momentSeconds;
                }

                $moment = $momentHours.":".$momentMinutes.":".$momentSeconds;

                if ($moment == "00:00:00") { $moment = null; }

                if (!empty($time) and !empty($visao) and !empty($ordem)) {

                    $sqlAlterTime = "UPDATE sv_tempo SET tempoTotal = :tempoTotal, tempoVisao = :tempoVisao, tempoMomento = :tempoMomento, tempoOrdem = :tempoOrdem WHERE tempoId = :tempoId";

                    try{

                        $queryAlterTime = $conecta->prepare($sqlAlterTime);
                        $queryAlterTime->bindValue(":tempoTotal", $time, PDO::PARAM_STR);
                        $queryAlterTime->bindValue(":tempoVisao", $visao, PDO::PARAM_STR);
                        $queryAlterTime->bindValue(":tempoMomento", $moment, PDO::PARAM_STR);
                        $queryAlterTime->bindValue(":tempoOrdem", $ordem, PDO::PARAM_STR);
                        $queryAlterTime->bindValue(":tempoId", $alterar, PDO::PARAM_STR);
                        $queryAlterTime->execute();

                        echo "<div class='alertaP'> Alteração realizada com sucesso.</div>";
                        echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/tempo'>";

                    }catch(PDOException $erroAlterTime){
                        echo "Erro ao atualizar o tempo";
                    }
                }
            }
        ?>
        
        <h1>Alterando Tempo</h1>

        <form id="criandoTempo" name="criaTempo" method="post" action="">

            <?php

                /*Realizar consulta e retornar valores no form*/

                $sqlSelectTime = "SELECT * FROM sv_tempo WHERE tempoId = :tempoId";

                try{

                    $querySelectTime = $conecta->prepare($sqlSelectTime);
                    $querySelectTime->bindValue(":tempoId", $alterar, PDO::PARAM_STR);
                    $querySelectTime->execute();

                    $resultSelectTime = $querySelectTime->fetchAll(PDO::FETCH_ASSOC);

                }catch(PDOException $erroSelectTime){
                    echo "Erro ao selecionar regras de tempo";
                }

                foreach ($resultSelectTime as $resultado) {
                    
                    $bdTotal = $resultado['tempoTotal'];
                    $bdVisao = $resultado['tempoVisao'];
                    $bdMomento = $resultado['tempoMomento'];
                    $bdOrdem = $resultado['tempoOrdem'];

                    /*
                      Tratando dados para jogar no inputs começando pelo tempo total
                      que chega em segundos ai faço a conversão para horas no  formato HH:mm:ss
                    */

                    $timeConverted = explode(":", convertTempo($bdTotal), 3);

                    //horas abaixo
                    $tfHora = $timeConverted[0];
                    //minutos abaixo
                    $tfMinutos = $timeConverted[1];
                    //segundos abaixo
                    $tfSeconds = $timeConverted[2];

                    if($bdMomento != null){

                        $momentConverted = explode(":", $bdMomento, 3);

                        $tfMomentH = $momentConverted[0];
                        $tfMomentM = $momentConverted[1];
                        $tfMomentS = $momentConverted[2];

                    }
                }

            ?>

            <label>
                <strong>Ordem: </strong>
                </label>
                <select class="sltAlter" name="ordem">
                    <?php 
                        if ($bdOrdem = "Crescente") {
                            echo "<option value='Crescente'>Crescente</option>"; 
                            echo "<option value='Decrescente'>Decrescente</option>";
                        }elseif($bdOrdem = "Decrescente" ){
                            echo "<option value='Decrescente'>Decrescente</option>"; 
                            echo "<option value='Crescente'>Crescente</option>";
                        }
                    ?>
                </select>
            


            <label>
                <strong>Tempo: </strong>
            </label>
                <input type="text" name="tempoHora" value="<?php echo $tfHora ; ?>" class="time" size="1"/>
                <input type="text" name="tempoMinuto" value="<?php echo $tfMinutos; ?>" class="time" size="1"/>
                <input type="text" name="tempoSegundo" value="<?php echo $tfSeconds; ?>" class="time" size="1"/>
            

            <label>
                <strong>Visibilidade Inicial: </strong>
            </label>

                <select class="sltAlter" name="visibilidade">
                    <?php 

                        if ($bdVisao = "normal") {
                            echo "<option value='normal'>Visível</option>";
                            echo "<option value='invisivel'>Invisível</option>";
                        }elseif($bdVisao = "invisivel"){
                            echo "<option value='invisivel'>Invisível</option>";
                            echo "<option value='normal'>Visível</option>";
                        }
                    ?>
                </select>
            

            <label>
                <strong>Mudando Em: </strong>
            </label>

                <input type="text" name="momentoHora" value="<?php if($bdMomento != null){ echo $tfMomentH;} else{ echo "00";} ?>" class="time" size="1"/>
                <input type="text" name="momentoMinuto" value="<?php if($bdMomento != null){ echo $tfMomentM;} else{ echo "00";} ?>" class="time" size="1"/>
                <input type="text" name="momentoSegundo" value="<?php if($bdMomento != null){ echo $tfMomentS;} else{ echo "00";} ?>" class="time" size="1"/>
            

            <input type="submit" name="btnAlterTempo" class="newbtn" value="Salvar"/>

        </form>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php 

include_once 'footer.php'; 

} else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/tempo'>";
    exit;
}
?>