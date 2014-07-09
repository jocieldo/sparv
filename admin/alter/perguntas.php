<?php 

if(isset($_GET['id']) && !empty($_GET['id'])){
    
    include_once 'system/restrito.php';
    include_once 'header.php';
    
    try{
        
        $sql_txtInstrucao = "SELECT perguntaTxt FROM sv_perguntas WHERE perguntaId = :perguntaId";
        $query_txtInstrucao = $conecta->prepare($sql_txtInstrucao);
        $query_txtInstrucao->bindValue(":perguntaId", $_GET['id'], PDO::PARAM_STR);
        $query_txtInstrucao->execute();
        
        $resultado_txtPergunta = $query_txtInstrucao->fetchAll(PDO::FETCH_ASSOC);
        
    }catch (PDOException $erro_pegaTxt){
        echo "Erro ao selecionar pegunta";
    }

    foreach ($resultado_txtPergunta as $res_txtPergunta);
    $txtInstrucao = $res_txtPergunta['perguntaTxt'];

?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/perguntas'>Perguntas</a> &gt; Alterar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="criapergunta">
        
        <!-- <h1 class="alertaN">Mensagem teste para essa caixa de texto com mensagens informativas</h1> -->
        <?php 
                
            if (isset($_POST['btn_perguntaAlter']) && !empty($_POST['perguntaemTxt'])){

                try{

                    $sql_UpInstrucao = "UPDATE  sv_perguntas SET  perguntaTxt = :perguntaTxt WHERE perguntaId = :perguntaId";
                    $query_UpInstrucao = $conecta->prepare($sql_UpInstrucao);
                    $query_UpInstrucao->bindValue(":perguntaTxt", $_POST['perguntaemTxt'], PDO::PARAM_STR);
                    $query_UpInstrucao->bindValue(":perguntaId", $_GET['id'], PDO::PARAM_STR);
                    $query_UpInstrucao->execute();
                    
                    echo "<h1 class='alertaP'>Pergunta alterada com sucesso!</h1>";
                    echo "<meta http-equiv='refresh' content='3;url=painel.php?spv=nav/perguntas'>";

                }catch (PDOException $erro_AtualizaPergunta){
                    echo "Erro ao atualizar pergunta".$erro_AtualizaPergunta->getMessage();
                }
            }
        ?>
        
        <h1>Alterar Pergunta</h1>
        <form name="criarpergunta" method="post" action="" >
            <label>
                <textarea name="perguntaemTxt" rows="5"><?php echo $txtInstrucao; ?></textarea>
            </label>
            
            <input type="submit" name="btn_perguntaAlter" class="newbtn" value="Alterar" />
        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php 

}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/perguntas'>";
    exit;
}

include_once 'footer.php'; 

?>