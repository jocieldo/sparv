<?php 
    include_once 'system/restrito.php';
    include_once '../Connections/config.php';
    include_once 'header.php';
?>

<script type="text/javascript" src="js/assocInstrucao.js"></script>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/modelos"> Modelos</a> &gt; Assosiar Instruções
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

                $instArray  = $_POST['idInstrucao'];
                $orderArray = $_POST['ordem'];

            }

        ?>

        <h1>Criando Modelos</h1>

        <form name="criaModelo" method='POST' action=''>

            <h2>Associando Instruções</h2>

            <img src="images/4.png" class="btnNewInst btnHover" title="Inserir nova instrução" />

            <label id="meuTemplate" for="criaAssoc" class="instAssoc" style="display:none">

                <strong>ID:</strong>
                <input name="idInstrucao[]" type="text">
                <strong>Ordem:</strong>
                <input name="ordem[]"type="text">

                <span>
                    <strong class="instTxt">Instrução:</strong>
                    <textarea disabled="disabled"></textarea>
                </span>

                <img src="images/1.png" title="Remover instrução" class="botaoExcluir" >

            </label>  

            <input type="submit" name="btnCriaModelo" class="newbtn" value="Criar"> 

        </form>

    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>