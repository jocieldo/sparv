<?php 

include_once 'system/restrito.php'; 

include_once '../Connections/config.php'; 
include_once '../Connections/arrayCon.php'; 
include_once 'header.php'; 

// Configuração do script
// ========================
$_BS['PorPagina'] = 21; // Número de registros por página

// Verifica se foi feita alguma busca
// Caso contrario, redireciona o visitante
if (!isset($_GET['verso'], $_GET['cor'], $_GET['simbolo'])) {
    echo "<meta http-equiv='refresh' content='0;url=painel.php?spv=nav/cartas'>";
    exit;
}

if (!isset($_GET['numero'])) { $numero = ""; }else{ $numero = $_GET['numero']; }
$cor = $_GET['cor'];
$simbolo = $_GET['simbolo'];
$verso = $_GET['verso'];

// Se houve busca, continue o script:

// Salva o que foi buscado em uma variável
$busca = $numero.$cor.$simbolo.$verso;
// Usa a função mysql_real_escape_string() para evitar erros no MySQL
$busca = mysql_real_escape_string($busca);

?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; <a href="painel.php?spv=nav/cartas"> Cartas </a>  &gt; Buscar
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagcartas">
        <form name="filterCard" method="get" action="painel.php">

            <input type="hidden" name="spv" value="search/buscaonecard" /> 

                <fieldset>
                    
                    <legend>Filtrar:</legend>
                    
                    <script type="text/javascript">
                        
                        $(document).ready(function (){
                            
                            $(":radio[value=F]").change(function (){
                                    $("select[name=numero]").removeAttr("disabled");
                            });
                        
                            $(":radio[value=V]").change(function (){
                                    $("select[name=numero]").attr("disabled", true);
                            });                          
                        
                        });
                        
                    </script>
                    
                    <span>Verso:</span>
                    <input type="radio" value="V" name="verso"><strong> Sim</strong>
                    <input type="radio" value="F" checked="checked" name="verso"><strong> Não</strong>
                    
                    <span>|</span>
                    
                    <span>Número:</span>
                    <select name="numero">
                        <option value="">Nº</option>
                        <option value="1">1</option>       
                        <option value="2">2</option>   
                        <option value="3">3</option>   
                        <option value="5">4</option>   
                        <option value="6">6</option>   
                        <option value="7">7</option>   
                        <option value="8">8</option>   
                        <option value="9">9</option>   
                        <option value="10">10</option>  
                        <option value="11">11</option>  
                        <option value="12">12</option>  
                        <option value="13">13</option>
                    </select>
                    
                    <span>Cor:</span>
                    <select name="cor">
                        <option value="">Selecione</option>                     
                        <option value="AMR">Amarela</option>
                        <option value="LRJ">Laranja</option>
                        <option value="AZL">Azul</option>
                        <option value="LIL">Lilas</option>
                        <option value="MAR">Marrom</option>
                        <option value="PRT">Preta</option>
                        <option value="VRD">Verde</option>
                        <option value="VRM">Vermelha</option>                        
                    </select>

                    <span>Simbolo:</span>
                    <select name="simbolo">
                        <option value="">Selecione</option>       
                        <option value="A">Arroba</option>
                        <option value="E">Estrela</option>
                        <option value="L">Losango</option>
                        <option value="C">Mais</option>
                        <option value="O">Orbitais</option>
                        <option value="Q">Quadrado</option>
                        <option value="R">Roda</option>
                        <option value="T">Triangulo</option>
                    </select>

                    <input type="submit" name="btnFiltar" class="btnFiltar" value="Filtrar" />

                </fieldset>
            </form>

            <form name="searchCard" class="searchCard" method="GET" action="painel.php">
                    <fieldset style='padding: 7px'>
                        <legend>Pesquisar: </legend>
                        <span>Nome:</span>
                        <input type="hidden" name="spv" value="search/buscacard" /> 
                        <input type="text" name="consulta" />
                        <input type="submit" name="btnSearch" class="btnSearch" value="Buscar" />            
                    </fieldset>
            </form>

        <ul>
        <?php

        // Monta a consulta MySQL para saber quantos registros serão encontrados
        $sql = "SELECT COUNT(*) AS total FROM `sv_cartas` WHERE cartaDesc = '".$busca."'";
        // Executa a consulta
        $query = mysql_query($sql);
        // Salva o valor da coluna 'total' e conteém o valor do total de registros retornados 
        $total = mysql_result($query, 0, 'total');
        // Calcula o máximo de paginas
        $paginas =  (($total % $_BS['PorPagina']) > 0) ? (int)($total / $_BS['PorPagina']) + 1 : ($total / $_BS['PorPagina']);

        // ============================================

        // Sistema simples de paginação, verifica se há algum argumento 'pagina' na URL
        if (isset($_GET['pagina'])) {
        $pagina = (int)$_GET['pagina'];
        } else {
        $pagina = 1;
        }
        $pagina = max(min($paginas, $pagina), 1);
        $inicio = ($pagina - 1) * $_BS['PorPagina'];

        // ============================================


        // Monta outra consulta MySQL, agora a que fará a busca com paginação
        $sql = "SELECT * FROM `sv_cartas` WHERE cartaDesc = '".$busca."' ORDER BY `cartaNumero` DESC LIMIT ".$inicio.", ".$_BS['PorPagina'];
        // Executa a consulta
        $query = mysql_query($sql);

        // ============================================

        // Começa a exibição dos resultados
        echo "<p id='visor'>".min($total, ($inicio + 1))." - ".min($total, ($inicio + $_BS['PorPagina']))." de ".$total." cartas</p>";
        // <p>Resultados 1 - 20 de 138 resultados encontrados para 'minha busca'</p>

        while ($resultado = mysql_fetch_assoc($query)) {
         
         $descricao = $resultado['cartaDesc'];
         $cor = $resultado['cartaCor'];
         $simbolo = $resultado['cartaSimbolo'];
         $numero = $resultado['cartaNumero'];
         $diretorio = $resultado['cartaDirectory'];

          echo "<li>";
            echo "<img src='../cartas/".$diretorio."' alt='".$descricao."' title='Número:".$numero.", Cor:".$cor.", Símbolo: ".$simbolo." '/>";
            echo "<strong>Carta: </strong><span>".$descricao."</span>";
          echo "</li>";
        }
        echo "</div>";
        echo " </ul>";

        // ============================================

        $max_links = 6;

        $links_laterais = ceil($max_links/2);

        $comeco = $pagina - $links_laterais;
        $fim = $pagina + $links_laterais;

        // Começa a exibição dos paginadores
            if ($total > 0) {
            echo "<div id='paginator'>";
            echo "<a class='pag' href='painel.php?spv=search/buscacard&consulta=".$busca."&pagina=1'> Primeira </a>";
            for ($i = $comeco; $i <= $fim; $i++)
            {
             if ($i == $pagina)
             {
              if ($i<10) { $i = "0".$i; }
              echo " <a class='pag' href='#'>" . $i . "</a> ";
             }
             else
             {
              if ($i >= 1 && $i <= $paginas)
              {
                if ($i<10) { $i = "0".$i; }
               echo '<a href="painel.php?spv=search/buscacard&consulta='.$busca.'&pagina='.$i.'">'.$i.'</a>&nbsp;&nbsp;';
              }
             }
            }

            echo '<a href="painel.php?spv=search/buscacard&consulta='.$busca.'&pagina='.$paginas.'"> Ultima </a>&nbsp;&nbsp;';

        }

        ?>
        
    </div><!-- painel pagfases -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>