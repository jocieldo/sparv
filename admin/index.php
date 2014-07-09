<?php require_once('../Connections/painel_game.php'); ?>
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

if (isset($_POST['tf_user'])) {
  $loginUsername=$_POST['tf_user'];
  $password=  sha1($_POST['tf_password']);
  $MM_fldUserAuthorization = "admAccess";
  $MM_redirectLoginSuccess = "painel.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_painel_game, $painel_game);
  	
  $LoginRS__query=sprintf("SELECT admEmail, admSenha, admAccess FROM sv_adm WHERE admEmail=%s AND admSenha=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $painel_game) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'admAccess');
    
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
        <link href="styles/login_style.css" type="text/css" rel="stylesheet" />        
        <title>SPARV</title>
    </head>
    <body>
        <div id="content">            
            <div id="header">
                <img src="../images/logos/brasao_ufc.png" title="Universidade Federal do Ceará" />
                <img src="../images/logos/logoleac.png" title="LEAC" />
            </div>
            <div id="corpo">
                <form name="form_log" id="form_log" method="POST" action="<?php echo $loginFormAction; ?>">
                    <div id="form_content">
                    Login:<br/>
                    <input class="tf" name="tf_user" type="text" value="" /><br/>
                    Senha:<br/>
                    <input class="tf" name="tf_password" type="password" /><br/>
                    <input name="btn_entrar" id="btn_entrar" type="submit" value="Entrar"/>
                    </div>
                </form>                
            </div>
            <div id="footer">
                <p>Copyright © 2013 UFC / LEAC. All rights reserved. Developed by InforTech Tecnologias.</p>
            </div>
        </div>
    
    </body>
</html>
