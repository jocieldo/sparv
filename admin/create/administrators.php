<?php 
include_once 'system/restrito.php';
include_once 'header.php';
include_once "../Connections/config.php";
?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários</a> &gt; <a href='painel.php?spv=nav/administrators'>Adiministradores (*somente o super)</a> &gt; Criar
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

                if(!empty($_POST['nome']) and !empty($_POST['sobrenome']) and !empty($_POST['email']) and !empty($_POST['senha'])){

                    $nome      = ucfirst(trim($_POST['nome']));
                    $sobrenome = ucwords(trim($_POST['sobrenome']));
                    $email     = trim($_POST['email']);
                    $senha     = sha1(trim($_POST['senha']));
                    $acesso    = $_POST['acesso'];


                    $sql = "INSERT INTO sv_adm (admNome, admSobrenome, admEmail, admSenha, admAccess)".
                                      " VALUES (:admNome, :admSobrenome, :admEmail, :admSenha, :admAccess)";

                    try{

                        $query = $conecta->prepare($sql);
                        $query->bindValue(":admNome", $nome, PDO::PARAM_STR);
                        $query->bindValue(":admSobrenome", $sobrenome, PDO::PARAM_STR);
                        $query->bindValue(":admEmail", $email, PDO::PARAM_STR);
                        $query->bindValue(":admSenha", $senha, PDO::PARAM_STR);
                        $query->bindValue(":admAccess", $acesso, PDO::PARAM_STR);
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

        <h1>Criando Administrador</h1>

        <form name="criaAdm" method="POST" action="">
            <label for="lbNome">
                <strong>Nome:</strong>
                <input name="nome" type="text">
            </label>
            <label for="lbSobrenome">
                <strong>Sobrenome</strong>
                <input name="sobrenome" type="text">
            </label>
            <label for="lbEmail">
                <strong>E-mail:</strong>
                <input name="email" type="text">
            </label>
            <label for="lbSenha">
                <strong>Senha:</strong>
                <input name="senha" type="password">
            </label>
            <label for="lbAcesso">
                <strong>Acesso:</strong>
            </label>
            <select name="acesso">
                <option value="no">Não</option>
                <option value="yes">Sim</option>
            </select>

            <input type="submit" name="btnCriaAdm" value="Criar" class="newbtn">
        </form>
    
    </div><!-- PagModelos -->

</div><!-- Content -->

<?php include_once "footer.php"; ?>