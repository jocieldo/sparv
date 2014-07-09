<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_painel_game = "localhost";
$database_painel_game = "sparv";
$username_painel_game = "root";
$password_painel_game = "";
$painel_game = mysql_pconnect($hostname_painel_game, $username_painel_game, $password_painel_game) or trigger_error(mysql_error(),E_USER_ERROR); 
?>