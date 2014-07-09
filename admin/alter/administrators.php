<?php 
include_once 'system/restrito.php';

if(isset($_GET['admin'])){

    include_once 'header.php';
    include_once "../Connections/config.php";

    $admin = $_GET['admin'];
    $admin = (int)$admin;

    $sqlAdm = "SELECT * FROM sv_adm WHERE admID = :admID";

    try{

        $queryAdm = $conecta->prepare($sqlAdm);
        $queryAdm->bindValue(":admID", $admin, PDO::PARAM_STR);
        $queryAdm->execute();

        $resultAdmm = $queryAdm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultAdmm as $valAdm) {
            $admNome      = ucfirst($valAdm['admNome']);
            $admSobrenome = ucwords(($valAdm['admSobrenome']));
            $admEmail     = $valAdm['admEmail'];
            $admAcesso    = $valAdm['admAccess'];
        }

    }catch(PDOException $erroAdm){
        echo "<div class='alertaN'>Erro ao selecionar administrador!</div>";
    }

?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários<a/> &gt; <a href='painel.php?spv=nav/administrators'>Adiministradores (*somente o super) </a> &gt; Alterar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class='pagmodelos'>

        <?php 

            if(isset($_POST['btnCriaAdm'])){

                if(!empty($_POST['nome']) and !empty($_POST['sobrenome']) and !empty($_POST['email'])){

                    $nome      = ucfirst(trim($_POST['nome']));
                    $sobrenome = ucwords(trim($_POST['sobrenome']));
                    $email     = trim($_POST['email']);
                    $acesso    = $_POST['acesso'];


                    $sql = "UPDATE sv_adm SET admNome = :admNome, admSobrenome = :admSobrenome, admEmail = :admEmail, admAccess = :admAccess WHERE admID = :admID";

                    try{

                        $query = $conecta->prepare($sql);
                        $query->bindValue(":admNome", $nome, PDO::PARAM_STR);
                        $query->bindValue(":admSobrenome", $sobrenome, PDO::PARAM_STR);
                        $query->bindValue(":admEmail", $email, PDO::PARAM_STR);
                        $query->bindValue(":admAccess", $acesso, PDO::PARAM_STR);
                        $query->bindValue(":admID", $admin, PDO::PARAM_STR);
                        $query->execute();

                        echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/administrators'>";
                        exit;

                    }catch(PDOException $erro){
                        echo "<div class='alertaN'>Erro ao criar novo administrador!</div>";
                    }

                }else{
                    echo "<div class='alertaN'>Algum campo em branco. Verifique e tente novamente!</div>";
                }

            }

        ?>

        <h1>Alterando Administrador</h1>

        <form name="criaAdm" method="POST" action="">
            <label for="lbNome">
                <strong>Nome:</strong>
                <input name="nome" value="<?php echo $admNome;?>" type="text">
            </label>
            <label for="lbSobrenome">
                <strong>Sobrenome</strong>
                <input name="sobrenome" value="<?php echo $admSobrenome;?>" type="text">
            </label>
            <label for="lbEmail">
                <strong>E-mail:</strong>
                <input name="email" value="<?php echo $admEmail;?>" type="text">
            </label>
            <label for="lbAcesso">
                <strong>Acesso:</strong>
            </label>
            <select name="acesso">
                <?php 
                    if($admAcesso == 'yes'){
                        echo "<option value='yes'>Sim</option>";
                        echo "<option value='no'>Não</option>";
                    }else{
                        echo "<option value='no'>Não</option>";
                        echo "<option value='yes'>Sim</option>";
                    }
                ?>
                
            </select>

            <input type="submit" name="btnCriaAdm" value="Salvar" class="newbtn">

        </form>
    
    </div><!-- PagModelos -->

</div><!-- Content -->

<?php 
    
    include_once "footer.php"; 

}else{

    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/administrators'>";
    exit;
    
}
?>
