<?php 
include_once 'system/restrito.php';
include_once 'header.php';
?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/instrucoes'>Instruções</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="criarInstrucao">
        <?php 
            if (isset($_POST['btn_Instrucao']) && !empty($_POST['instrucaoemTxt'])){
                
                    $instrucaoEscrita = $_POST['instrucaoemTxt'];

                    try{

                        $sql_criaInstrucao = "INSERT INTO sv_instrucoes (instrucaoTxt) values (:instrucaoTxt)";
                        $query_criaInstrucao = $conecta->prepare($sql_criaInstrucao);
                        $query_criaInstrucao->bindValue(":instrucaoTxt", $instrucaoEscrita, PDO::PARAM_STR);
                        $query_criaInstrucao->execute();
                        
                        echo "<h1 class='alertaP''>Instrução criada com sucesso! Você será redenrizado para pagina de perguntas...</h1>";
                        echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/instrucoes'>";

                    }catch(PDOException $erro_criarPergunta){
                        echo "Erro ao criar pergunta";
                    }
                                
                }
        ?>
        
        <h1>Cria Instrução</h1>
        <form name="criarInstrucao" method="post" action="" >
            <label>
                <textarea name="instrucaoemTxt" rows="5"></textarea>
            </label>
            
            <input type="submit" name="btn_Instrucao" class="newbtn" value="Criar" />
        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>