<?php 



if(isset($_GET['modelo']) and !empty($_GET['modelo'])){

    $modelId = $_GET['modelo'];
    $modelId = (int)$modelId;

    include_once 'header.php';
    include_once '../Connections/config.php';
    include_once "funcs/functions.php";
    include_once 'system/restrito.php';

?>   

<script type="text/javascript" src="js/assocInstrucao.js"></script>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Modelos
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">
<?php 
    if(isset($_POST['btnAlteraTimeModel'])){
        if(!empty($_POST['regraID'])){
            
            $sqlVerifica = "SELECT * FROM sv_tempo WHERE tempoId = :tempoId";

            try {
                $queryVerifica = $conecta->prepare($sqlVerifica);
                $queryVerifica->bindValue(":tempoId", trim($_POST['regraID']), PDO::PARAM_STR);
                $queryVerifica->execute();

                $resulVerifica = $queryVerifica->rowCount(PDO::FETCH_ASSOC);

                if($resulVerifica != 0 && $resulVerifica == 1){

                    $sqlUP = "UPDATE sv_modelos SET modelTempoId = :modelTempoId WHERE modelId = :modelId";

                    try {

                        $queryUP = $conecta->prepare($sqlUP);
                        $queryUP->bindValue(":modelTempoId", trim($_POST['regraID']), PDO::PARAM_STR);
                        $queryUP->bindValue(":modelId", $modelId, PDO::PARAM_STR);
                        $queryUP->execute();

                        header("location: painel.php?spv=nav/instrucoesAssoc&modelo=$modelId");
                        
                    } catch (PDOException $er) {
                        echo "<h1 class='alertaN'>Ocorreu um erro durante a alteração da definição de tempo!</h1>";
                    }

                }else{
                    echo "<h1 class='alertaN'>Não existe nenhuma definição de tempo associada a este modelo!</h1>";
                }

            } catch (PDOException $e) {
                echo "<h1 class='alertaN'>Ocorreu um erro!</h1>";
            }
        }else{
            echo "<h1 class='alertaN'>O campo ID está vazio!</h1>";
            //echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/perguntas'>";
        }
    }
?>
    
    <h1>Alterando Definção de Tempo</h1>

    <div class="pagmodelos">
        <form method="post" action="">
            <p>Informe a abaixo o ID da regra de tempo para a qual deseja alterar.</p>
            <input name="regraID" class="issoaqui" type="text">
            <input type="submit" name="btnAlteraTimeModel" class="newbtn" value="Alterar">
        </form>
    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php 
    
    include_once 'footer.php'; 
}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php'>";
    exit;
}
?>