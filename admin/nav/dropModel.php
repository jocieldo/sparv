<?php 
    
    include_once 'system/restrito.php'; 
    include_once '../Connections/config.php';
    include_once 'funcs/functions.php';
    include_once 'header.php'; 

?>

    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Modelos
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">
        <h1>Excluir Modelo</h1>
        <form method="get" action="painel.php">

            <input type="hidden" name="spv" value="drop/dropModel">

            <label for="modeloNome">Modelo:</label>
            <select name="modelo">
                <option value="">Selecione</option>
                <?php 

                    $sql = "SELECT * FROM sv_modelos";

                    try {
                        $query = $conecta->prepare($sql);
                        $query->execute();

                        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($resultado as $value) {
                            echo "<option value=".$value['modelId'].">".$value['modelNome']."</option>";
                        }

                    } catch (PDOException $e) {
                        echo "<option value=''>Erro</option>";
                    }
                ?>

                
            </select>
            <br>
            <input style="display: block;" type="submit" class="newbtn" name="btnDropModel" value="Excluir">
        </form>
    </div><!-- painel pagmodelos -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>           