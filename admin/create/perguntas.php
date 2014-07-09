<?php 
include_once 'system/restrito.php';
include_once 'header.php';
include_once '../Connections/config.php';
?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/perguntas'>Perguntas</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="criapergunta">
        <?php 
            if (isset($_POST['btn_pergunta']) && !empty($_POST['perguntaemTxt'])){
                
                    $instrucaoEscrita = $_POST['perguntaemTxt'];

                    try{

                        $sql_criaInstrucao = "INSERT INTO sv_perguntas (perguntaTxt) values (:perguntaTxt)";
                        $query_criaInstrucao = $conecta->prepare($sql_criaInstrucao);
                        $query_criaInstrucao->bindValue(":perguntaTxt", $instrucaoEscrita, PDO::PARAM_STR);
                        $query_criaInstrucao->execute();
                        
                        echo "<h1 class='alertaP'>Pergunta criada com sucesso! Você será redenrizado para pagina de perguntas...</h1>";
                        echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/perguntas'>";

                    }catch(PDOException $erro_criarPergunta){
                        echo "<div class='alerta'>Erro ao criar pergunta</div>";
                    }
                                
                }
        ?>
        <!-- <h1 class="alertaN">Mensagem teste para essa caixa de texto com mensagens informativas</h1> -->
        
        <h1>Criar Pergunta</h1>
        <form name="criarpergunta" method="post" action="" >
            <label>
                <textarea name="perguntaemTxt" rows="5"></textarea>
            </label>
            
            <input type="submit" name="btn_pergunta" class="newbtn" value="Criar" />
        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>