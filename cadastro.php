<?php include_once 'Connections/config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'java/scripts.php';?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="styles/cadastro.css" type="text/css" rel="stylesheet" />
        <title>SPARV</title>
    </head>
    <body>
        <div id="header">
            <img src="images/logos/brasao_ufc.png" title="Universidade Federal do Ceará" />
            <img src="images/logos/logoleac.jpg" title="LEAC" />
        </div><!-- fecha Header -->
        
                <?php
                            if (isset($_POST['btn_cadastrar'])){

                                $userIncritoEm = date("Y-m-d H:i:s");
                                $userNome = $_POST['nome'];
                                $userSobrenome = $_POST['sobrenome'];
                                $userSexo = $_POST['sexo'];
                                $userEscolaridade = $_POST['userEscolaridade'];
                                $userDataNascimento = $_POST['dataNascimento'];
                                $userEmail = $_POST['email'];
                                $userSenha = sha1($_POST['senha']);
                                $userNivel = "nao";
                                $userStatus = "pendente";
                                $userToken = uniqid(time()); /*gera um cod aleatórios e unico com 25 caracteres*/
                                $userModel = 0;
                                
                                try {
                                    
                                    $sql_verificaMail = "SELECT * FROM sv_users WHERE userEmail = :userEmail";
                                    
                                    $query_verificaMail = $conecta->prepare($sql_verificaMail);
                                    $query_verificaMail->bindValue(":userEmail", $userEmail, PDO::PARAM_STR);
                                    $query_verificaMail->execute();
                                    
                                    $result_verificaMail = $query_verificaMail->rowCount(PDO::FETCH_ASSOC);                        
                                    
                                } catch (PDOException $erro_verificaMail) {
                                    
                                }
                                
                                if ($result_verificaMail >= 1){
                                    echo "<div class=\"noenvie\">Email já cadastrado no sistema, use outro e-mail por favor.</div>";
                                }else{
                                    
                                    $sql_cadastraUser = "INSERT INTO sv_users (userIncritoEm, "
                                                                            . "userNome, "
                                                                            . "userSobrenome, "
                                                                            . "userSexo, "
                                                                            . "userEscolaridade, "
                                                                            . "userDataNascimento, "
                                                                            . "userEmail, "
                                                                            . "userSenha, "
                                                                            . "userNivel, "
                                                                            . "userStatus, "
                                                                            . "userModelId, "
                                                                            . "userToken)";
                                    
                                    $sql_cadastraUser .= "VALUES (:userIncritoEm, "
                                                               . ":userNome, "
                                                               . ":userSobrenome, "
                                                               . ":userSexo, "
                                                               . ":userEscolaridade, "
                                                               . ":userDataNascimento, "
                                                               . ":userEmail, "
                                                               . ":userSenha, "
                                                               . ":userNivel, "
                                                               . ":userStatus, "
                                                               . ":userModelId,"
                                                               . ":userToken)";

                                    try {

                                        $query_inscreveUser = $conecta->prepare($sql_cadastraUser);
                                        $query_inscreveUser->bindValue(":userIncritoEm", $userIncritoEm,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userNome", $userNome,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userSobrenome", $userSobrenome,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userSexo", $userSexo,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userEscolaridade", $userEscolaridade,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userDataNascimento", $userDataNascimento,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userEmail", $userEmail,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userSenha", $userSenha,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userNivel", $userNivel,PDO::PARAM_STR);
                                        $query_inscreveUser->bindValue(":userStatus", $userStatus,PDO::PARAM_STR);                                        
                                        $query_inscreveUser->bindValue(":userModelId", $userModel,PDO::PARAM_STR);  
                                        $query_inscreveUser->bindValue(":userToken", $userToken,PDO::PARAM_STR);
                                        $query_inscreveUser->execute();
                                        
                                        
                                        $para = $userEmail;
                                        $assunto = "Confirmando Cadastro - SPARV";
                                        $messagem = "<p style='margin: 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>"
                                                  . "Olá, $userNome. Seu cadastro foi realizado com sucesso.</p> "
                                                  . "<p style='margin: 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'> Mas, precisamos "
                                                  . "que aguarde a liberação de seu acesso por parte dos adiministradores do sistema.</p>"
                                                  . "<p style='margin: 5px 0 0 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>Segue abaixo seus dados de acesso:</p>"
                                                  . "<p style='margin: 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>Login(e-mail*): <a style='text-decoration: none; color: #333;'>".$userEmail."</a></p>"
                                                  . "<p style='margin: 0 0 5px 0; padding:0; font: 12px Arial, Heveltica, sans-serif; color: #333; display:block;'>Senha: ".$_POST['senha']."</p>"
                                                  . "<p style='margin: 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>Inscrição em: ".date("d/m/Y H:i:s")." </p>"
                                                  . "<p style='margin: 0; padding:0; font: 9px Arial, Heveltica, sans-serif; color: #333; display:block;'>Qualquer dúvida escreva para sparv@cemp.com.br</p>";

                                        $headers  = "MIME-Version: 1.0\n";
                                        $headers .= "Content-Type:text/html; charset=UTF-8\n";
                                        $headers .= "From: SPARV 1.0<sparv@cemp.com.br>\n"; //Vai ser mostrado que o email partiu deste email e seguido do nome
                                        $headers .= "X-Sender: <sparv@cemp.com.br>\n"; //email do servidor que enviou
                                        $headers .= "X-Mailer: PHP v".phpversion()."\n";
                                        $headers .= "X-IP: ".$_SERVER['REMOTE_ADDR']."\n";
                                        $headers .= "Return-Path: <sparv@cemp.com.br>\n"; //caso a msg seja respondida vai para este email.

                                        $envio = mail($para, $assunto, $messagem, $headers);

                                        if ($envio){
                                            echo "<div class=\"envie\">Cadastrado com sucesso! Enviamos um email de confirmação para <strong>$userEmail</strong>.</div>";
                                            
                                        }else{
                                            echo "<div class='noenvie'> Erro ao realizar a confirmação do cadastro.</div>";
                                        }

                                    } catch (PDOException $ex) {
                                        echo "<div class='noenvie'>Erro ao realizar o cadastro .".$ex->getMessage()."</div>";
                                    }
                                
                                }

                            }                  
                        ?>
            
        <form name="form_cadastro" id="form_cadastro" action="#" method="post">
                <fieldset>                    
                    
                    <label>
                        <span>Primeiro Nome:</span>
                        <input type="text" name="nome" size="15"/>
                    </label>
                    
                    <label>
                        <span>Sobrenome Completo:</span>
                        <input type="text" name="sobrenome" size="30" />
                    </label>
                    <label>
                        <span>Escolaridade:</span>                        
                    </label>
                    
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Fundamental Incompleto"/>  Fundamental Incompleto<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Fundamental Completo"/>  Fundamental Completo<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" checked="checked" value="Médio Completo"/>  Médio Completo<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Médio Incompleto"/>  Médio Incompleto<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Superior Completo"/>  Superior Completo<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Superior Incompleto"/>  Superior Incompleto<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Pós-Graduação Completa"/>  Pós-Graduação Completa<br/>
                    <input type="radio" class="nivelEscolar" name="userEscolaridade" value="Pós-Graduação Incompleta"/>  Pós-Graduação Incompleta<br/>
                    
                    <label>
                        <span>Data de Nascimento:</span>
                        <input type="text" size="10" id="dataNascimento" name="dataNascimento" />
                    </label>
                    
                    <label>
                        <span>Sexo:</span>
                        <select name="sexo">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </label>
                    
                    <label>
                        <span>E-mail:</span>
                        <input type="text" size="40" name="email" />
                    </label>
                    
                    <label>
                        <span>Senha:</span>
                        <input type="password" size="16" name="senha" />
                    </label>                    
                    
                    <input class="btn_cadastrar" type="submit" name="btn_cadastrar" value="Cadastrar" />
                    
                </fieldset>
            </form>
        
        <div id="footer">
                <img src="images/logo_mkt.png" title="InforTech Tecnologias e Marketing" alt="InforTech Tecnologias e Marketing" />
                <p>Copyright © 2013 UFC / LEAC. All rights reserved.</p>                
        </div><!-- fecha footer-->
    </body>
</html>