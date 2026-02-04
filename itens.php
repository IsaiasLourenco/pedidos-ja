<?php
require_once("./cabecalho.php");
$url = $_GET['url'];
$query = $pdo->query("SELECT * FROM categorias WHERE url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $id_categoria = $res[0]['id'];
    $nome_categoria = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
}
?>

<div class="main-container">
    <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="index" class="link-neutro"><i class="bi bi-arrow-left"></i></a>
                <span class="ms-2"><?php echo strtoupper($nome_categoria) ?></span>
            </div>
            <?php require_once("./icone-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mt-6">
        <?php
        $query = $pdo->query("SELECT * FROM produtos WHERE categoria = '$id_categoria' AND ativo = 'Sim'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = count($res);
        if ($total_reg > 0) {
            for ($i = 0; $i < $total_reg; $i++) {
                $id_produto = $res[$i]['id'];
                $nome_produto = $res[$i]['nome'];
                $foto = $res[$i]['foto'];
                $estoque = $res[$i]['estoque'];
                $nivel_estoque = $res[$i]['nivel_estoque'];
                $valor_produto = $res[$i]['valor_venda'];
                $valorProdFormatado = 'R$ ' . number_format($valor_produto, 2, ',', '.');
                $url_produto = $res[$i]['url'];
                if ($estoque > 0 and $estoque <= $nivel_estoque) {
                    $mostrar = 'ocultar';
                } else {
                    $mostrar = '';
                } ?>
                <a href="produto-<?php echo $url_produto; ?>" class="link-neutro <?php echo $mostrar ?>">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold titulo-item"><?php echo $nome_produto ?></div>
                            <?php
                            $queryVar = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND ativo = 'Sim'");
                            $resVar = $queryVar->fetchAll(PDO::FETCH_ASSOC);
                            $total_regVar = count($resVar);
                            if ($total_regVar > 0) {
                                $partes = [];
                                foreach ($resVar as $variacao) {
                                    $sigla = $variacao['sigla'];
                                    $valorVar = $variacao['valor'];
                                    $valorVarFormatado = 'R$ ' . number_format($valorVar, 2, ',', '.');
                                    $partes[] = "$sigla - $valorVarFormatado";
                                }
                                echo '<span class="valor-item">' . implode(' | ', $partes) . '</span>';
                            } else {
                                echo '<span class="valor-item">' . $valorProdFormatado . '</span>';
                            }
                            ?>
                        </div>
                        <i class="bi bi-caret-left-square-fill text-primary"></i>
                    </li>
                </a>
        <?php   }
        } ?>
    </ol>

</div>

<?php require_once("./rodape.php"); ?>

</body>

</html>