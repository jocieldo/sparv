<?php

/*Definindo horário local segundo GMT*/
date_default_timezone_set("America/Fortaleza");

define("HOST", "localhost");
define("DB", "sparv");
define("USER", "root");
define("PASS", "");

$conexao = 'mysql:host='.HOST.';dbname='.DB."; charset=utf8";

$opcoes = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
);

try {

    $conecta = new PDO($conexao, USER, PASS, $opcoes);
    $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $error_conecta) {
    echo "Erro ao conectar ".$error_conecta->getMessage();
}

?>