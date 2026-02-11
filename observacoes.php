<?php
session_start();
require_once("./cabecalho.php");
$url_completa       = $_GET['url'];

$sessao = $_SESSION['sessao_usuario'];

$separar_url        = explode("_", $url_completa);
$url                = $separar_url['0'];
$item               = $separar_url['1'];

$queryCar              = $pdo->query("SELECT * FROM carrinho WHERE sessao = '$sessao'");
$resCar                = $queryCar->fetchAll(PDO::FETCH_ASSOC);
$total_regCar          = count($resCar);
if ($total_regCar > 0) {
    $id_cliente = $resCar[0]['id_cliente'];

    $queryCli              = $pdo->query("SELECT * FROM cliente WHERE id = '$id_cliente'");
    $resCli                = $queryCli->fetchAll(PDO::FETCH_ASSOC);
    $total_regCli          = count($resCli);
    if ($total_regCli > 0) {
        $nome_cliente = $resCli[0]['nome'];
        $telefone_cliente = $resCli[0]['telefone'];
    }
}

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

    $queryTemp = $pdo->query("SELECT * FROM carrinho_temp WHERE sessao = '$sessao' AND tabela   = 'adicionais'
                                                                                   AND carrinho = '0'");
    $resTemp = $queryTemp->fetchAll(PDO::FETCH_ASSOC);
    $total_regTemp = count($resTemp);
    if ($total_regTemp > 0) {
        for ($i = 0; $i < $total_regTemp; $i++) {
            $id_item = $resTemp[$i]['id_item'];
            $queryAd = $pdo->query("SELECT * FROM adicionais WHERE  id = '$id_item'");
            $resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);
            $valor_adicional = $resAd[0]['valor'];
            $valor_item += $valor_adicional;
        }
    }
    $valor_itemF = 'R$ ' . number_format($valor_item, 2, ',', '.');
}

$queryAd            = $pdo->query("SELECT COUNT(*) as total FROM adicionais WHERE produto = '$id_produto'");
$total_adicionais   = $queryAd->fetch()['total'];

$queryIng           = $pdo->query("SELECT COUNT(*) as total FROM ingredientes WHERE produto = '$id_produto'");
$total_ingredientes = $queryIng->fetch()['total'];

$tem_adicionais_ou_ingredientes = ($total_adicionais > 0 || $total_ingredientes > 0);

if ($aberto == "Fechado") {
    echo "<script>window.alert('$texto_fecha_urg')</script>";
    echo "<script>window.location='index'</script>";
    exit;
}

//Verificar se está aberto ou fechado
$data               = date('Y-m-d');
$diasSemana         = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
$diasSemana_numero  = date('w', strtotime($data));
$dia_procurado      = $diasSemana[$diasSemana_numero];
$query              = $pdo->query("SELECT * FROM dias WHERE dia = '$dia_procurado'");
$res                = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    echo "<script>window.alert('$texto_fecha_dia')</script>";
    echo "<script>window.location='index'</script>";
    exit;
}
//Verificar horário de funcionamento
$hora_atual = date('H:i');
if (strtotime($fechamento) < strtotime($abertura)) {
    if (strtotime($hora_atual) >= strtotime($abertura) || strtotime($hora_atual) <= strtotime($fechamento)) {
        
    } else {
        echo "<script>window.alert('$texto_fecha_hora   ')</script>";
        echo "<script>window.location='index'</script>";
        exit;
    }
} else {
    if (strtotime($hora_atual) >= strtotime($abertura) && strtotime($hora_atual) <= strtotime($fechamento)) {
        
    } else {
        echo "<script>window.alert('$texto_fecha_hora')</script>";
        echo "<script>window.location='index'</script>";
        exit;
    }
}
?>

<div class="main-container fundo mt-6">
    <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <?php if (!empty($item) && $tem_adicionais_ou_ingredientes): ?>
                    <a href="adicional-<?php echo $url_completa ?>" class="link-neutro"><i class="bi bi-arrow-left"></i></a>    
                <?php else: ?>
                    <a href="produto-<?php echo $url ?>" class="link-neutro"><i class="bi bi-arrow-left"></i></a>
                <?php endif; ?>
                <span class="ms-2">Resumo do Item</span>
            </div>
            <?php require_once("./icone-carrinho.php"); ?>
        </div>
    </nav>

    <div class="destaque">
        <strong>
            <span>
                <?php echo mb_strtoupper($nome_produto) ?>
                <?php if (!empty($item)): ?>
                    | <?php echo $item; ?>
                <?php endif; ?>
            </span>
        </strong>
    </div>

    <div class="qtd">
        <strong>Quantidade&nbsp;</strong>
        <span>
            <a href="#" onclick="diminuiQtd()"><i class="bi bi-dash-circle-fill text-danger icone-qtd"></i></a>
            <span class="fw-bold valor-qtd" id="qtd">1</span>
            <a href="#" onclick="aumentaQtd()"><i class="bi bi-plus-circle-fill text-success icone-qtd"></i></a>
        </span>
    </div>
    <input type="hidden" id="quantidade" value="1">
    <input type="hidden" id="entrada-total-item" value="<?php echo $valor_item ?>">
    <div class="obs">
        <strong>OBSERVAÇÕES</strong>
        <div class="form-group">
            <textarea name="obs" id="obs" class="textarea-observacoes"></textarea>
        </div>
    </div>

    <div class="total-item" id="total-item">
        <strong>Total do item - <?php echo $valor_itemF ?></strong>
    </div>

    <a href="#popup-cliente" class="btn btn-primary w-100 mt-5">
        Adicionar ao carrinho →
    </a>

</div>

<?php require_once("./rodape.php"); ?>

<script type="text/javascript">
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape" && window.location.hash === "#popup-cliente") {
            window.location.hash = "";
        }
    });

    $(document).ready(function() {
        var qtd = $("#quantidade").val();
        $("#qtd").text(qtd);
    });

    function aumentaQtd() {
        var qtd = parseInt($("#quantidade").val());
        var novaQtd = qtd + 1;
        $("#qtd").text(novaQtd);
        $("#quantidade").val(novaQtd);

        var total_inicial = <?php echo $valor_item; ?>;
        var valor_total = total_inicial * novaQtd;

        $("#total-item").html('<strong>Total do item - ' + formatarMoeda(valor_total) + '</strong>');
        $("#entrada-total-item").val(valor_total);
    }

    function diminuiQtd() {
        var qtd = parseInt($("#quantidade").val());
        if (qtd > 1) {
            var novaQtd = qtd - 1;
            $("#qtd").text(novaQtd);
            $("#quantidade").val(novaQtd);

            var total_inicial = <?php echo $valor_item; ?>;
            var valor_total = total_inicial * novaQtd;

            $("#total-item").html('<strong>Total do item - ' + formatarMoeda(valor_total) + '</strong>');
            $("#entrada-total-item").val(valor_total);
        }
    }

    function formatarMoeda(valor) {
        return 'R$ ' + valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        });
    }

    function buscarNome() {
        var tel = $('#telefone').val();

        $.ajax({
            url: 'js/ajax/listar-nome.php',
            method: 'POST',
            data: {
                tel
            },
            dataType: 'text',

            success: function(result) {
                $('#cliente-nome').val(result);
            }
        })
    }

    function comprarMais() {
        var telefone = $('#telefone').val();
        var nome = $('#cliente-nome').val();
        if (telefone == '') {
            alert('Entre com um número de telefone válido!');
            return;
        }
        if (nome == '') {
            alert('Entre com o seu nome para concluir a compra!');
            return;
        }
        addCarrinho();
        setTimeout(redirecionar, 2000);
    }

    function redirecionar() {
        window.location = 'index';
    }

    function finalizarPedido() {
        var telefone = $('#telefone').val();
        var nome = $('#cliente-nome').val();
        if (telefone == '') {
            alert('Entre com um número de telefone válido!');
            return;
        }
        if (nome == '') {
            alert('Entre com o seu nome para concluir a compra!');
            return;
        }
        addCarrinho();
        setTimeout(redirecionarCarrinho, 2000);
    }

    function redirecionarCarrinho() {
        window.location = 'carrinho';
    }

    function addCarrinho() {
        var telefone = $('#telefone').val();
        var nome = $('#cliente-nome').val();
        var quantidade = $('#quantidade').val();
        var total_item = $('#entrada-total-item').val();
        var produto = <?php echo $id_produto; ?>;
        var obs = $('#obs').val();;
        $.ajax({
            url: 'js/ajax/add-carrinho.php',
            method: 'POST',
            data: {
                telefone,
                nome,
                quantidade,
                total_item,
                produto,
                obs
            },
            dataType: 'text',
            success: function(mensagem) {
                if (mensagem.trim() == 'Inserido com sucesso!') {};
            },
            error: function(xhr, status, error) {
                alert("Erro na requisição: " + xhr.responseText);
            }
        })
    }
</script>

</body>

</html>

<!-- MODAL CLIENTE COMPRAR MAIS -->
<div id="popup-cliente" class="overlay-cliente">
    <div class="popup-cliente">
        <a href="#" class="close-cliente">&times;</a>

        <form action="carrinho.php" method="post">
            <h5 class="titulo-popup mg-b-2">Identificação do Cliente</h5>

            <div class="mg-b-2">
                <label>Telefone</label>
                <input onkeyup="buscarNome()"
                    type="text"
                    id="telefone"
                    class="form-control"
                    placeholder="(00) 00000-0000"
                    value="<?php echo @$telefone_cliente ?>">
            </div>

            <div class="mg-b-2">
                <label>Nome</label>
                <input onclick="buscarNome()"
                    type="text"
                    id="cliente-nome"
                    class="form-control"
                    placeholder="Seu nome"
                    value="<?php echo @$nome_cliente ?>">
            </div>

            <div class="d-flex gap-2">
                <a href="#" onclick="comprarMais()" type="button" class="btn btn-primary w-50 mt-2 mb-2">
                    Comprar Mais
                </a>

                <a href="#" onclick="finalizarPedido()" type="button" class="btn btn-success w-50 mt-2 mb-2">
                    Finalizar Pedido
                </a>
                <hr>
            </div>
        </form>
        <div class="mensagem"></div>
    </div>
</div>
<!-- FIM MODAL CLIENTE COMPRAR MAIS -->