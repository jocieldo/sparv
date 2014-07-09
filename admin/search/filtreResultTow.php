<?php

include_once 'funcs/functions.php';

$where = Array();

if(isset($_POST['sexo'])){ 
  $where[] = " sv_users.userSexo = '".$_POST['sexo']."'"; 
}

if(isset($_POST['faixa']) and !empty($_POST['faixaNumber'])){

  $hojeYear = date("Y", strtotime("today"));
  $valorAno = $_POST['faixaNumber'];
  $valorAno = $hojeYear-$valorAno;
  $valorAno = (int)$valorAno;

  $where[] = "CONVERT(RIGHT(sv_users.userDataNascimento, 4), SIGNED) ".$_POST['faixa']." $valorAno"; 

}

if(!empty($_POST['modelo'])){ $where[] = " sv_modelos.modelId = ".$_POST['modelo']; }
if(!empty($_POST['data'])){ $where[] = " sv_jogo.`Data` LIKE '".retornaData($_POST['data'])."%'"; }


  
//SQL para selecionar dados da tabela sv_jogo na base de dados
$sqlSJ = 'SELECT '. 
         'sv_users.userID as `usuarioId`, '.
         'sv_users.userNome as `nome`, '.
         'sv_users.userSobrenome as `sobrenome`, '.
         'sv_users.userDataNascimento as `idade`, '.
         'sv_users.userSexo as `sexo`, '.
         'sv_users.userEscolaridade as `escolaridade`, '.
         'sv_modelos.modelNome as `modeloNome`, '.
         'sv_jogo.Modelo, '.
         'sv_jogo.`Data` as hora '.
         'FROM sv_jogo '.
         'INNER JOIN sv_users ON sv_jogo.UsuarioId = sv_users.userID '.
         'INNER JOIN sv_modelos ON sv_jogo.Modelo = sv_modelos.modelId';

    if( sizeof( $where ) )

      $sqlSJ .= ' WHERE '.implode( ' AND ',$where );
 
    echo $sqlSJ;//execute a query aqui