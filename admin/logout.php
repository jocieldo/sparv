<?php
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
                <img src="images/logos/logoleac.jpg" title="LEAC" />
            </div>
            <div id="corpo">
                <form name="form_log" id="form_log" method="POST" action="#">
                    <div id="form_content">
                        <h1>Delogado com sucesso!!!</h1>
                        <span class="msg">
                            <strong>Aviso:</strong><br/><br/>
                            É sempre interessante usar senhas fortes para dificultar ações cybernéticas delituosas.<br/><br/>
                        </span>
                        
                        <a class="voltar" href="index.php">Voltar logar</a>
                    </div><!-- form content -->
                </form><!-- fecha formulário de login -->                
            </div><!-- fecha corpo -->
            <div id="footer">
                <p>Copyright © 2013 UFC / LEAC. All rights reserved. Developed by InforTech Tecnologias.</p>
            </div><!-- fecha footer -->
        </div><!--fecha content -->
    
    </body>
</html>