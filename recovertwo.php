<?php 

include_once "Connections/config.php";

try{
    $sql_verificaToken = "SELECT * FROM sv_users WHERE userToken = :userToken";
    
    $query_verificaToken = $conecta->prepare($sql_verificaToken);
    $query_verificaToken->bindValue(":userToken", $_GET['tk'], PDO::PARAM_STR);
    $query_verificaToken->execute();
    
    $resultRow_verificaToken = $query_verificaToken->rowCount(PDO::FETCH_ASSOC);
    $resultFields_verificaToken = $query_verificaToken->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resultFields_verificaToken as $usuario);
    
    $usuarioId = $usuario['userID'];
    $usuarioNome = $usuario['userNome'];
    $usuarioEmail = $usuario['userEmail'];
    $usuarioSenha = $usuario['userSenha'];
    $usuarioToken = $usuario['userToken'];
    
}  catch (PDOException $erro_verificaToken){
    echo "Erro ao verifica autenticação do usuário";
    header("location: http://www.cemp.com.br/sparv/");
    exit;
}

if (isset($_GET['tk']) && strlen($_GET['tk']) == 23 && $resultRow_verificaToken == 1){

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="styles/recover.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript"></script>
        <title>SPARV</title>
    </head>
    <body>
        <div id="content">  
            
            <div id="header">
                <img src="images/logos/brasao_ufc.png" title="Universidade Federal do Ceará" />
                <img src="images/logos/logoleac.jpg" title="LEAC" />
            </div>
            
            <form name="form_log" method="POST" action="">                
                <span class="envie">Digite abaixo sua nova senha:</span>
                    <label>
                        <span>Senha:</span>
                        <input type="password" name="senha" />
                    </label>
                
                   <input type="submit" class="btn_recuperar_senha" name="redefinir_pass" value="Redefinir" />
                   
                   <?php 
                        
                        if(isset($_POST['redefinir_pass'])){
                            
                            $sql_alteraSenha = "UPDATE sv_users SET userSenha = :userSenha WHERE userID = $usuarioId";
                            
                            try{
                                
                                $query_alteraSenha = $conecta->prepare($sql_alteraSenha);
                                $query_alteraSenha->bindValue(":userSenha", sha1($_POST['senha']), PDO::PARAM_STR);
                                $query_alteraSenha->execute();
                                
                                $headers  = "MIME-Version: 1.0\n";
                                $headers .= "Content-Type:text/html; charset=UTF-8\n";
                                $headers .= "From: SPARV 1.0<sparv@cemp.com.br>\n"; //Vai ser mostrado que o email partiu deste email e seguido do nome
                                $headers .= "X-Sender: <sparv@cemp.com.br>\n"; //email do servidor que enviou
                                $headers .= "X-Mailer: PHP v".phpversion()."\n";
                                $headers .= "X-IP: ".$_SERVER['REMOTE_ADDR']."\n";
                                $headers .= "Return-Path: <sparv@cemp.com.br>\n"; //caso a msg seja respondida vai para este email.
                                
                                $para = $usuarioEmail;
                                $assunto = "Comprovante de Troca de Senha - SPARV \n";
                                $messagem = "<p style='margin: 0 0 5px 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                          . "Olá, $usuarioNome. Sua nova senha é:</p> "                                      
                                          . "<p style='margin: 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                          . "Senha: ".$_POST['senha']."</p>"

                                          . "<p style='margin: 10px 0 0 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                          . "Dia/Hora da redefinção de senha: ".date("d/m/Y H:i:s")." </p>"
                                          . "<p style='margin: 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                          . "Se esta pessoa não for você por favor escreva urgentemente para sparv@cemp.com.br. Informando o ocorrido.</p>";

                                $envio = mail($para, $assunto, $messagem, $headers);

                                if ($envio == true){  
                                    echo "<div class=\"alertas\">Senha redefinida com sucesso!</div>";
                                    echo "<script type='text/javascript'> $('.envie').css('display', 'none');</script>";  
                                    echo "<meta http-equiv='refresh' content='3; url=http://www.cemp.com.br/sparv'>";
                                }else if($envio == false){
                                    echo "<div class='envie'> Erro no processo final de redefinição de senha.</div>";
                                }
                                
                                
                            
                            }  catch (PDOException $erro_alteraSenha){
                                echo "<div class=\"error\">Erro ao redefinir senha no banco!</div> ".$erro_alteraSenha->getMessage();
                            }
                        }
                   ?>
                        
                        <!-- <div class="alertas">Enviamos seu dados de redefinição para seu e-mail!</div> -->                   
            </form>
            <div id="footer">                
                <img src="images/logo_mkt.png" title="InforTech Tecnologias e Marketing" alt="InforTech Tecnologias e Marketing" />
                <p>Copyright © 2013 UFC / LEAC. All rights reserved.</p>                                
            </div><!-- fecha footer-->
        </div>    
    </body>
</html>
<?php 
}else{
    
    echo 'final';
    header("location: http://www.cemp.com.br/sparv/");
    exit;
}
?>