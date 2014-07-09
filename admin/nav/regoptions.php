<?php include_once 'system/restrito.php'; ?>
<?php include_once 'header.php'; ?>
                
    <div id="local">

        <div class="caminho">
            Painel Administrativo &gt; Regras Extras
        </div><!-- fecha caminho -->

        <div class="welcome">
            Olá, <span><?php echo $admNome; ?></span>. <a href="logout.php">Sair</a>
        </div><!-- fecha welcome -->

    </div><!-- fecha local -->

</div><!-- Header -->

<div id="content">

    <div class="pagmodelos">            

    <div class="btnPage" id="esquerda">
        <a href="painel.php?spv=nav/regTime">Regras Tempo</a>
    </div>
    <div class="btnPage" id="direita">
        <a href="painel.php?spv=nav/regMove">Regras Movimentação</a>
    </div>

    </div><!-- painel table -->

</div><!-- Content -->
            
<?php include_once 'footer.php'; ?>           