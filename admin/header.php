<?php 
    
    include_once 'system/restrito.php';
    include_once '../Connections/config.php';
    
    $admEmail = $_SESSION['MM_Username'];
    
    $sql_selecionaAllAdms = "SELECT * FROM sv_adm WHERE admEmail = :admEmail";
    
    try {
        
        $query_selecionaAllAdms = $conecta->prepare($sql_selecionaAllAdms);
        $query_selecionaAllAdms->bindValue(":admEmail", $admEmail, PDO::PARAM_STR);
        $query_selecionaAllAdms->execute();
        
        $result_selecionaAllAdms = $query_selecionaAllAdms->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $erro__selecionaAllAdms) {
        echo "Erro ao selecionar dados do administrador";
    }
    
    foreach ($result_selecionaAllAdms as $resultados);      
        
        $admID = $resultados['admID'];
        $admNome = $resultados['admNome'];
        $admSobrenome = $resultados['admSobrenome'];
        
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="styles/painel_style.css" type="text/css" rel="stylesheet" />
        <script src="js/jquery-2.0.2.min.js" type="text/javascript"></script>        
        <title>SPARV | Administração</title>
    </head>
    <body>        
        <div id="box">
            
            <div id="header">
                
                <div class="menu_bar_superior">
                    <ul>
                        <li><a href="painel.php?spv=nav/modelos">Modelos</a></li>
                        <li><a href="painel.php?spv=nav/fases">Fases</a></li>
                        <li><a href="painel.php?spv=nav/perguntas">Perguntas</a></li>
                        <li><a href="painel.php?spv=nav/instrucoes">Instruções</a></li>
                        <li><a href="painel.php?spv=nav/tempo">Tempo</a></li>
                        <li><a href="painel.php?spv=nav/regoptions">Regras Extras</a></li>
                        <li><a href="painel.php?spv=nav/cartas">Cartas</a></li>
                        <li><a href="painel.php?spv=nav/usuarios">Usuários</a></li>
                        <li><a href="painel.php?spv=nav/resultados">Resultados</a></li>
                        
                    </ul>
                </div><!-- menu bar superior -->
                
                <div class="menu_bar_inferior"></div><!-- menu bar inferior | obs: efeito abaixo do menu -->
               