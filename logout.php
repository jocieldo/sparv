<?php
/*Codigo abaixo grava no banco quando o user clica em cancelar e sair na aplicação*/
if(isset($_GET['usr'], $_GET['md'])){

   $usuario = (int)$_GET['usr'];
   $modelo  = (int)$_GET['md']; 
   $hora    = date("Y-m-d H:i:s");

    include_once ("Connections/config.php");

    try{

        $sql_Cancela = "INSERT INTO sv_cancel ( cancelUser, cancelModel, cancelData) values(:cancelUser, :cancelModel, :cancelData)";

        $query_Cancela = $conecta->prepare($sql_Cancela);
        $query_Cancela->bindValue(":cancelUser", $usuario, PDO::PARAM_STR);
        $query_Cancela->bindValue(":cancelModel", $modelo, PDO::PARAM_STR);
        $query_Cancela->bindValue(":cancelData", $hora, PDO::PARAM_STR);
        $query_Cancela->execute();

    }catch(PDOException $erro_Cancela){
        echo "Erro ao gravar cancelamento!";
    }

}

// *** Logout the current user.
$logoutGoTo = "";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="styles/index.css" type="text/css" rel="stylesheet" />
        <title>SPARV</title>
    </head>
    <body>
        <?php
          //todo o código PHP aqui
        ?>
        <div id="content">            
            <div id="header">
                <img src="images/logos/brasao_ufc.png" title="Universidade Federal do Ceará" />
                <img src="images/logos/logoleac.png" title="LEAC" />
            </div>
            <form>
                <fieldset>
                    <h1>Deslogado com sucesso...</h1>
                    <span class="msg">
                        Mensagem de agradecimentos por ter paticipado da pesquisa!
                    </span>
                </fieldset>
            </form>
            <div id="footer">
                
                <img src="images/logo_mkt.png" title="InforTech Tecnologias e Marketing" alt="InforTech Tecnologias e Marketing" />
                <p>Copyright © 2013 UFC / LEAC. All rights reserved.</p>                
                                
            </div><!-- fecha footer-->
        </div><!--fecha content -->
    
    </body>
</html>