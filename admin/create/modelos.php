<?php 
include_once 'system/restrito.php';
include_once '../Connections/config.php';
include_once 'header.php';
?>

<script type="text/javascript" src="js/createModel.js"></script>
<script type="text/javascript" src="js/jquery-2.0.2.min.js"></script>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/modelos"> Modelos</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">

         <?php 

            if(isset($_POST['btnCriaModelo'])){

                $nameModel = $_POST['nomeModelo'];
                $idTime    = $_POST['idTime'];

                if(!empty($nameModel) and !empty($idTime)){

                    $sqlSelect = "SELECT * FROM sv_modelos WHERE modelNome = :modeloNome";

                    try{

                        $querySelect = $conecta->prepare($sqlSelect);
                        $querySelect->bindValue(":modeloNome", $nameModel, PDO::PARAM_STR);
                        $querySelect->execute();

                        $resultRow = $querySelect->rowCount(PDO::FETCH_ASSOC);

                        if($resultRow = 0){
                            echo "<div class='alertaN'>Já existe um <strong>modelo</strong> com o mesmo nome. Escolha outro por favor!</div>";
                        }else{

                            $sql = "INSERT INTO sv_modelos (modelNome, modelTempoId) VALUES (:modelNome, :modelTempoId)";

                            try{

                                $query=$conecta->prepare($sql);
                                $query->bindValue(":modelNome", $nameModel, PDO::PARAM_STR);
                                $query->bindValue(":modelTempoId", $idTime, PDO::PARAM_STR);
                                $query->execute();

                                echo "<meta http-equiv='refresh' content='1;url=painel.php'>";
                                exit;

                            }catch(PDOException $erro){
                                echo "<div class='alertaN'>Erro ao criar novo modelo. Por favor entre em contato com o suporte.</div>";
                            }

                        }

                    }catch(PDOException $erroSelect){

                        echo "<div class='alertaN'>Erro ao selecionar modelo. Por favor entre em contato com o suporte.</div>";

                    }


                }else{
                    echo "<div class='alertaN'>Algum campo em branco, preencha-o e tente novamente.</div>";
                }

            }

        ?>

        <h1>Criando Modelos</h1>

        <form name="criaModelo" method='POST' action=''>

            <label for="name">
                <strong>Nome:</strong>
                <input name="nomeModelo" maxlength="20" type="text">
            </label>

            <h2>Associando Tempo</h2>
            <label class="time">
                <strong>ID:</strong>
                <input type="text" name="idTime" style="text-align: center; width:25px;">
                <div class="lbTime">
                    <strong>Total:</strong>
                    <span>HH:MM:SS</span>
                    <strong class='margem'>Visibilidade Inicial:</strong>
                    <span>Visível</span>
                    <strong class='margem'>Mudando Em:</strong>
                    <span>HH:MM:SS</span>
                    <strong class='margem'>Ordem:</strong>
                    <span>Crescente</span>
                </div>
            </label>

            <input type="submit" name="btnCriaModelo" class="newbtn" value="Criar"> 

        </form>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>