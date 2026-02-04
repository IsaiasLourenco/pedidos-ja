<?php require_once("./cabecalho.php"); ?>

<!-- Espaço compensando a altura da navbar fixa -->
<div style="height: 80px;"></div>

<div class="main-container">

<nav class="navbar bg-body-tertiary fixed-top sombra-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="index">
            <img src="img/<?= htmlspecialchars($logotipo) ?>" width="30" height="30" alt="Logo" class="d-inline-block align-text-top">
            <?php echo $nome_sistema ?>
        </a>
        <?php require_once("./icone-carrinho.php"); ?>
    </div>
</nav>

    <div class="row cards">
        <?php
        $query = $pdo->query("SELECT * FROM categorias WHERE ativo = 'Sim' AND insumo = 'Não'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
            for ($i = 0; $i < $total_reg; $i++) {
                $id = $res[$i]['id'];
                $nome = $res[$i]['nome'];
                $foto = $res[$i]['foto'];
                $cor = $res[$i]['cor'];
                $url = $res[$i]['url'];

                $queryProd = $pdo->query("SELECT * FROM produtos WHERE categoria = '$id' AND ativo = 'Sim'");
                $produtos = $queryProd->fetchAll(PDO::FETCH_ASSOC);
                $mostrar = 'ocultar';
                // Verifica se a categoria tem ALGUM produto com estoque > 0
                $usa_controle_estoque = false;
                foreach ($produtos as $produto) {
                    if ($produto['estoque'] > 0) {
                        $usa_controle_estoque = true;
                        break;
                    }
                }

                if ($usa_controle_estoque) {
                    // É categoria de revenda: aplica regra de estoque
                    $mostrar = 'ocultar';
                    foreach ($produtos as $produto) {
                        if ($produto['estoque'] > $produto['nivel_estoque']) {
                            $mostrar = '';
                            break;
                        }
                    }
                } else {
                    // É produção própria (estoque = 0): mostra SEMPRE
                    $mostrar = '';
                }
        ?>
                <div class="col-md-4 col-6 <?php echo $mostrar ?>">
                    <a href="categoria-<?php echo $url; ?>" class="link-card">
                        <?php if ($cards == 'Foto' && !empty($foto)): ?>
                            <!-- Modo com foto -->
                            <div class="card"
                                style="background-image: url('./sistema/painel/images/categorias/<?= htmlspecialchars($foto) ?>');
                                        background-size: cover;
                                        background-position: center;
                                        border: none;">
                                <div class="badge-title-card">
                                    <span><?= htmlspecialchars($nome) ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Modo com cor de fundo -->
                            <div class="card <?= htmlspecialchars($cor) ?>">
                                <h3 class="card_title"><?= htmlspecialchars($nome) ?></h3>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
        <?php }
        } ?>
    </div>
</div>
</div>

<?php require_once("./rodape.php"); ?>
</body>

</html>