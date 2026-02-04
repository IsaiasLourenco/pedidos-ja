<?php
require_once("./cabecalho.php");
$url = $_GET['url'];
$query = $pdo->query("SELECT * FROM produtos WHERE url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_produto             = $res[0]['id'];
    $nome_produto           = $res[0]['nome'];
    $descricao_produto      = $res[0]['descricao'];
    $valor_produto          = $res[0]['valor_venda'];
    $valorProdutoFormatado  = 'R$ ' . number_format($valor_produto, 2, ',', '.');
    $foto_produto           = $res[0]['foto'];
    $categoria_produto      = $res[0]['categoria'];
}
$queryCat = $pdo->query("SELECT * FROM categorias WHERE id = '$categoria_produto'");
$resCat = $queryCat->fetchAll(PDO::FETCH_ASSOC);
$url_categoria = $resCat[0]['url'];

// ✅ Verifica adicionais/ingredientes do PRODUTO PAI (não da variação)
$queryAd = $pdo->query("SELECT COUNT(*) as total FROM adicionais WHERE produto = '$id_produto'");
$total_adicionais = $queryAd->fetch()['total'];

$queryIng = $pdo->query("SELECT COUNT(*) as total FROM ingredientes WHERE produto = '$id_produto'");
$total_ingredientes = $queryIng->fetch()['total'];

$tem_adicionais_ou_ingredientes = ($total_adicionais > 0 || $total_ingredientes > 0);
?>

<div class="main-container">
    <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="categoria-<?php echo $url_categoria ?>" class="link-neutro"><i class="bi bi-arrow-left"></i></a>
                <span class="ms-2">Variações</span>
            </div>
            <?php require_once("./icone-carrinho.php"); ?>
        </div>
    </nav>
    <ol class="list-group mt-6">
        <?php
        $queryVar = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND ativo = 'Sim'");
        $resVar = $queryVar->fetchAll(PDO::FETCH_ASSOC);
        $total_regVar = count($resVar);
        if ($total_regVar > 0) {
            for ($i = 0; $i < $total_regVar; $i++) {
                $nome_variacao      = $resVar[$i]['nome'];
                $descricao_variacao = $resVar[$i]['descricao'];
                $sigla_variacao     = $resVar[$i]['sigla'];
                $valor_variacao     = $resVar[$i]['valor'];
                $valorVarFormatado  = 'R$ ' . number_format($valor_variacao, 2, ',', '.'); 
                
                if ($tem_adicionais_ou_ingredientes) { ?>
                    <a href="adicional-<?php echo $url ?>_<?php echo $sigla_variacao ?>" class="link-neutro">
                <?php
                } else { ?>
                    <a href="observacoes-<?php echo $url ?>_<?php echo $sigla_variacao ?>" class="link-neutro">
                <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold titulo-item"><?php echo $nome_variacao ?></div>
                            <div class="fw-bold titulo-item"><?php echo $sigla_variacao ?></div>
                            <span class="valor-item"><?php echo $valorVarFormatado ?></span>
                        </div>
                        <i class="bi bi-caret-left-square-fill text-primary"></i>
                    </li>
                </a>
            <?php }
        } else { 
            if ($tem_adicionais_ou_ingredientes) { ?>
                <a href="adicional-<?php echo $url ?>" class="link-neutro">
            <?php
            } else { ?>
                <a href="observacoes-<?php echo $url ?>" class="link-neutro">
            <?php } ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold titulo-item"><?php echo $nome_produto ?></div>
                        <span class="valor-item"><?php echo $valorProdutoFormatado ?></span>
                    </div>
                    <i class="bi bi-circle-fill text-primary"></i>
                </li>
            </a>
        <?php } ?>
    </ol>
    <div class="conteudo-descricao">
        <div class="titulo-descricao-item">
            <strong><?php echo $nome_produto ?></strong>
            <p class="descricao-item">
                <?php echo $descricao_produto ?>
            </p>
        </div>
    </div>
    <div>
        <img class="imagem-produto"
            src="sistema/painel/images/produtos/<?php echo $foto_produto ?>"
            alt="Foto do produto">
    </div>
</div>

<?php require_once("./rodape.php"); ?>

</body>

</html>