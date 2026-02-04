<?php
session_start();
require_once("./cabecalho.php");
$url_completa       = $_GET['url'];

if (!isset($_SESSION['sessao_usuario']) || empty($_SESSION['sessao_usuario'])) {
    $_SESSION['sessao_usuario'] = uniqid('sess_', true);
}
$sessao = $_SESSION['sessao_usuario'];

$separar_url        = explode("_", $url_completa);
$url                = $separar_url['0'];
$item               = $separar_url['1'];

$query              = $pdo->query("SELECT * FROM produtos WHERE url = '$url'");
$res                = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg          = count($res);
if ($total_reg > 0) {
    $id_produto             = $res[0]['id'];
    $nome_produto           = $res[0]['nome'];
    $descricao_produto      = $res[0]['descricao'];
    $valor_produto          = $res[0]['valor_venda'];
    $valorProdutoFormatado  = 'R$ ' . number_format($valor_produto, 2, ',', '.');
    $foto_produto           = $res[0]['foto'];
    $categoria_produto      = $res[0]['categoria'];
    $url_produto            = $res[0]['url'];

    if ($item == "") {
        $valor_item = $valor_produto;
    } else {
        $queryVar           = $pdo->query("SELECT * FROM variacoes WHERE produto = '$id_produto' AND sigla = '$item' AND ativo = 'Sim'");
        $resVar             = $queryVar->fetchAll(PDO::FETCH_ASSOC);
        $total_regVar          = count($resVar);
        if ($total_regVar > 0) {
            for ($i = 0; $i < $total_regVar; $i++) {
                $valor_item         = $resVar[$i]['valor'];
            }
        }
    }
    $valor_itemF            = 'R$ ' . number_format($valor_item, 2, ',', '.');
}
?>

<div class="main-container">
    <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="produto-<?php echo $url ?>" class="link-neutro"><i class="bi bi-arrow-left"></i></a>
                <span class="ms-2">
                    <?php echo $nome_produto; ?>
                    <?php if (!empty($item)): ?>
                        | <?php echo $item; ?>
                    <?php endif; ?>
                </span>
            </div>
            <?php require_once("./icone-carrinho.php"); ?>
        </div>
    </nav>

    <ol class="list-group mt-6" id="listar-adicionais">

    </ol>

    <ol class="list-group" id="listar-ing">

    </ol>

    <div class="total" id="listar-subtotal">

    </div>

    <a href="observacoes-<?php echo $url_completa?>" class="btn btn-primary w-100 botao-finalizar">
        Avançar →
    </a>

</div>

<?php require_once("./rodape.php"); ?>

<script type="text/javascript">
    $(document).ready(function() {
        listarAdicionais();
        listarIng();
        listarSubtotal();
    });

    function adicionar(id, acao) {
        $.ajax({
            url: 'js/ajax/adicionais.php',
            method: 'POST',
            data: {
                id,
                acao
            },
            dataType: 'text',

            success: function(mensagem) {
                if (mensagem.trim() == "Alterado com sucesso") {
                    listarAdicionais();
                    listarIng();
                    listarSubtotal();
                }
            },
        });
    }

    function listarAdicionais() {
        $.ajax({
            url: 'js/ajax/listar-adicionais.php',
            method: 'POST',
            data: {
                id_produto: <?php echo (int)$id_produto; ?>
            },
            dataType: 'html',

            success: function(result) {
                $('#listar-adicionais').html(result);
            },
        });
    }

    function adicionarIng(id, acao) {
        $.ajax({
            url: 'js/ajax/adicionar-ingredientes.php',
            method: 'POST',
            data: {
                id,
                acao
            },
            dataType: 'text',

            success: function(mensagem) {
                if (mensagem.trim() == "Alterado com sucesso") {
                    listarAdicionais();
                    listarIng();
                    listarSubtotal();
                }
            },
        });
    }

    function listarIng() {
        $.ajax({
            url: 'js/ajax/listar-ing.php',
            method: 'POST',
            data: {
                id_produto: <?php echo (int)$id_produto; ?>
            },
            dataType: 'html',

            success: function(result) {
                $('#listar-ing').html(result);
            },
        });
    }

    function listarSubtotal() {
        $.ajax({
            url: 'js/ajax/listar-subtotal.php',
            method: 'POST',
            data: {
                id_produto: <?php echo (int)$id_produto; ?>,
                valor_base: <?php echo $valor_item; ?>
            },
            dataType: 'html',

            success: function(result) {
                $('#listar-subtotal').html(result);
            },
        });
    }

</script>

</body>

</html>