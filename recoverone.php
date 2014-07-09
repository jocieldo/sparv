<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="styles/recover.css" type="text/css" rel="stylesheet" />        
        <script type="text/javascript" src="java/jquery.js"></script>  
        <title>SPARV</title>           
    </head>
    <body>
        <div id="content">  
            
            <div id="header">
                <img src="images/logos/brasao_ufc.png" title="Universidade Federal do Ceará" />
                <img src="images/logos/logoleac.jpg" title="LEAC" />              
            </div>
            
            <form name="form_log" method="POST" enctype="multipart/form-data" action="">                
                <span class="envie">Para redefinir sua senha, digite abaixo seu e-mail:</span>
                    <label>
                        <span>E-mail:</span>
                        <input type="text" name="email"/>
                    </label>
                
                        <input type="submit" class="btn_recuperar_senha" name="recuperar_senha" value="Enviar" />              
                        
                        <?php 

                        if(isset($_POST['recuperar_senha'])){

                            include_once 'Connections/config.php';

                            $emailUser = $_POST['email'];           

                            try {

                                $sql_selectToken = "SELECT userToken, userNome, userEmail FROM sv_users WHERE userEmail = :userEmail";
                                $query_selectToken = $conecta->prepare($sql_selectToken);
                                $query_selectToken->bindValue(":userEmail", $emailUser, PDO::PARAM_STR);
                                $query_selectToken->execute();

                                $resultRow_selectToken = $query_selectToken->rowCount(PDO::FETCH_ASSOC);
                                $resultFields_selectToken = $query_selectToken->fetchAll(PDO::FETCH_ASSOC);

                            } catch (Exception $erro_SelectToken) {

                            }

                            if($resultRow_selectToken == 0){
                                echo "<div class='error'>E-mail não cadastrado no sistema.</div>";
                            }  else {
                                foreach ($resultFields_selectToken as $token){

                                    date_default_timezone_set("America/Fortaleza");

                                    $tokenUser = $token['userToken'];
                                    $tokenUserName = $token['userNome'];
                                    $tokenUserEmail = $token['userEmail'];

                                    $headers  = "MIME-Version: 1.0\n";
                                    $headers .= "Content-Type:text/html; charset=UTF-8\n";
                                    $headers .= "From: SPARV 1.0<sparv@cemp.com.br>\n"; //Vai ser mostrado que o email partiu deste email e seguido do nome
                                    $headers .= "X-Sender: <sparv@cemp.com.br>\n"; //email do servidor que enviou
                                    $headers .= "X-Mailer: PHP v".phpversion()."\n";
                                    $headers .= "X-IP: ".$_SERVER['REMOTE_ADDR']."\n";
                                    $headers .= "Return-Path: <sparv@cemp.com.br>\n"; //caso a msg seja respondida vai para este email.
                                    
                                    $para = $token['userEmail'];
                                    $assunto = "Redefinir de Senha - SPARV";
                                    $messagem = "<p style='margin: 0 0 10px 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                              . "Olá, $tokenUserName. Para redefinir sua senha <a href='http://www.cemp.com.br/sparv/recovertwo.php?tk=$tokenUser' style='text-decoration: underline; font-weight: bold;'>Clique aqui</a>. Ou clique no link abaixo:</p> "                                      
                                              . "<p style='margin: 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                              . "Link: <a href='http://www.cemp.com.br/sparv/recovertwo.php?tk=$tokenUser' style='text-decoration: none; color: #333; font-weight: bold;'> http://www.cemp.com.br/sparv/recovertwo.php?tk=$tokenUser</a></p>"

                                              . "<p style='margin: 10px 0 0 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                              . "Dia do pedido de refefinção: ".date("d/m/Y H:i:s")." </p>"
                                              . "<p style='margin: 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                              . "Se esta pessoa não for você por favor escreva urgentemente para sparv@cemp.com.br. Informando o ocorrido.</p>";

                                    $envio = mail($para, $assunto, $messagem, $headers);
                                    
                                    if ($envio == true){  
                                        echo "<script type='text/javascript'> $('.envie').css('display', 'none');</script>";
                                        echo "<script type='text/javascript'> $('input:text').attr('value', '$emailUser');</script>";
                                        echo "<div class='alertas'>Enviamos seu dados de redefinição para seu e-mail!</div>";
                                        echo "<meta http-equiv='refresh' content='3; url=http://www.cemp.com.br/sparv'>";
                                    }else if($envio == false){
                                        echo "<div class='envie'> Erro no processo de redefinição de senha.</div>";
                                    }
                                    
                                }                        
                            }

                        }
                    ?>
            </form>   
           
            <div id="footer">                
                <img src="images/logo_mkt.png" title="InforTech Tecnologias e Marketing" alt="InforTech Tecnologias e Marketing" />
                <p>Copyright © 2013 UFC / LEAC. All rights reserved.</p>                                
            </div><!-- fecha footer-->
        </div>    
    </body>
</html>