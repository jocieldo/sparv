<?php 
		
		// Conexão com o MySQL
        // ========================
        $_BS['MySQL']['servidor'] = 'localhost';
        $_BS['MySQL']['usuario'] = 'root';
        $_BS['MySQL']['senha'] = '';
        $_BS['MySQL']['banco'] = 'sparv';

        //aqui realiza a conexão com o banco de dados
        mysql_connect($_BS['MySQL']['servidor'], $_BS['MySQL']['usuario'], $_BS['MySQL']['senha']);

        //selecionando a base de dados
        mysql_select_db($_BS['MySQL']['banco']);
        // ====(Fim da conexão)====
        
?>