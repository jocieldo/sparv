<?php require_once('Connections/painel_game.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['email'])) {
  $loginUsername=$_POST['email'];
  $password=sha1($_POST['senha']);
  $MM_fldUserAuthorization = "userNivel";
  $MM_redirectLoginSuccess = "game.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_painel_game, $painel_game);
  	
  $LoginRS__query=sprintf("SELECT userEmail, userSenha, userNivel FROM sv_users WHERE userEmail=%s AND userSenha=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $painel_game) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'userNivel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="java/jquery.js"></script>
        <script type="text/javascript" src="java/jquery_validate.js"></script>
        <script type="text/javascript">
            $(document).ready(function (){
                $("#form_log").validate({
                    rules:{
                        email:{required: true, email: true},
                        senha:{required: true}
                    },
                    messages:{
                        email:{required: "Este campo e-mail não pode ficar vazio*"},
                        senha:{required: "Insira sua senha por favor*"},
                    },
                });
            });
        </script>
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
            <form name="form_log" id="form_log" method="post" ACTION="<?php echo $loginFormAction; ?>">
                <fieldset>
                    <label for="lb_email">
                        <span>Login:(e-mail*)</span>
                        <input type="text" name="email" />
                    </label>
                    <label for="lb_email">
                        <span>Senha:</span>
                        <input type="password" name="senha" />
                    </label>

                    <input name="btn_entrar" id="btn_entrar" type="submit" value="Entrar" title="Clique aqui para efetuar logon."/>

                    <div class="errar">E-mail ou senha não conferem.</div> 

                </fieldset>
            </form>            
            <div id="footer">
                
                <img style="display: none" src="images/logo_mkt.png" title="InforTech Tecnologias e Marketing" alt="InforTech Tecnologias e Marketing" />
                <p>Copyright © 2013 UFC / LEAC. All rights reserved.</p>                
                                
            </div><!-- fecha footer-->
        </div><!-- fecha content -->
    </body>
</html>