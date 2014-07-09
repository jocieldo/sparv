<?php 
include_once 'system/restrito.php';
include_once 'header.php';
?>

                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href='painel.php?spv=nav/usuarios'>Usuários</a> &gt; Criar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="criausuario">
        <h1>Criar Usuário</h1>
        
        <form name="form_cadastro" class="form_cadastro" action="#" method="post">
                <fieldset>                    
                    
                    <label>
                        <span>Nome Completo:</span>
                        <input type="text" name="nome" size="80"/>
                    </label>
                    
                    <label>
                        <span>Apelido:</span>
                        <input type="text" class="apelido" size="30"/>
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
                        <input size="50" type="text" name="email" />
                    </label>
                    
                    <label>
                        <span>Senha:</span>
                        <input type="password" size="20" name="senha" />
                    </label>
                    
                    
                    <input class="btn_cadastrar" type="submit" name="btn_cadastrar" value="Cadastrar" />
                    
                </fieldset>
        </form>
    </div><!-- painel pagusers -->

</div><!-- Content -->

<?php include_once 'footer.php'; ?>