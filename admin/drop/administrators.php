<?php

include_once 'system/restrito.php';

if(isset($_GET['admin'])){

	include_once '../Connections/config.php';

	$admin = $_GET['admin'];
	$admin = (int)$admin;

	$sql = 'DELETE FROM sv_adm WHERE admID = :admID';

	try{

		$query = $conecta->prepare($sql);
		$query->bindValue(":admID", $admin, PDO::PARAM_STR);
		$query->execute();

		header("location: painel.php?spv=nav/administrators");
		exit;

	}catch(PDOException $erro){
		
		header("location: painel.php?spv=nav/administrators");
		exit;		

	}

}else{

	header("location: painel.php?spv=nav/administrators");
	exit;
	
}
?>