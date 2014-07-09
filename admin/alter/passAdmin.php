<?php 
    
    include_once 'system/restrito.php';

if(isset($_GET['admin'])){

    include_once 'header.php'; 

?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários</a> &gt; <a href='painel.php?spv=nav/administrators'>Administradores (*somente o super)</a> &gt; Alterar Senha
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">   

    <?php  

        if(isset($_POST['btnAlterPass'])){

            include_once '../Connections/config.php';

            $senhaOne = $_POST['senha1'];
            $senhaTwo = $_POST['senha2'];

            if(!empty($senhaOne) and !empty($senhaTwo)){

                if($senha1 === $senha2){

                    $sql = "UPDATE sv_adm SET admSenha = :admSenha WHERE admID = :admID";

                    try{

                        $query = $conecta->prepare($sql);
                        $query->bindValue(":admSenha", sha1($senha1), PDO::PARAM_STR);
                        $query->bindValue(":admID", $_GET['admin'], PDO::PARAM_STR);
                        $query->execute();

                        echo "<div class='alertaP'>Senha alterada com suceso.</div>";
                        echo "<meta http-equiv='refresh' content='2;url=painel.php?spv=nav/administrators' />";

                    }catch(PDOException $erro){
                        echo "<div class='alertaN'>Erro ao atualizar senha.</div>";      
                    }

                }else{
                    echo "<div class='alertaN'>Senhas diferentes. Coloque os mesmos valores em ambos os campos.</div>";  
                }

            }else{
                echo "<div class='alertaN'>Algum campo em branco tente novamente.</div>";    
            }
            
        }

    ?>         

        <h1>Alterando Senha de Adminstrador</h1>

        <form name="alterPassword" method="POST" action="">
            
            <label for="alterarSenha">

                <strong>Senha:</strong>
                <input name='senha1' type="text">
                <strong>Repita a Senha:</strong>
                <input name='senha2' type="text">
                
                <input type="submit" style="margin-top: -2px;" name="btnAlterPass" value="Alterar" class="newbtn">

            </label>

        </form>         
    </div><!-- painel table -->

</div><!-- Content -->
            
<?php 

include_once 'footer.php'; 

}else{
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/administrators'>";
    exit;
}

?>
