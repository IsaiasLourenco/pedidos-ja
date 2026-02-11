<?php
session_start();
require_once("./cabecalho.php");

$sessao = $_SESSION['sessao_usuario'];
$valor_total_carrinho  = 0;
$valor_total_carrinhoF = 0;
$query = $pdo->query("SELECT * FROM carrinho WHERE sessao = '$sessao'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg  = count($res);
$id_cliente = $res[0]['id_cliente'];

$queryCli = $pdo->query("SELECT * FROM cliente WHERE id = '$id_cliente'");
$resCli = $queryCli->fetchAll(PDO::FETCH_ASSOC);
$total_regCli       = count($resCli);
$nome_cliente       = $resCli[0]['nome'];
$telefone_cliente   = $resCli[0]['telefone'];
$rua_cliente        = $resCli[0]['rua'];
$numero_cliente     = $resCli[0]['numero'];
$id_bairro_cliente  = $resCli[0]['bairro_id'];
$cidade_cliente     = $resCli[0]['cidade'];
$estado_cliente     = $resCli[0]['estado'];
$cep_cliente        = $resCli[0]['cep'];

$queryBairro = $pdo->query("SELECT * FROM bairros WHERE id = '$id_bairro_cliente'");
$resBairro = $queryBairro->fetchAll(PDO::FETCH_ASSOC);
if (count($resBairro) > 0) {
    $valor_entrega  = $resBairro[0]['valor'];
    $nome_bairro    = $resBairro[0]['nome'];
} else {
    $valor_entrega  = 0;
}
$valor_entregaF  = 'R$' . number_format($valor_entrega, 2, ',', '.');


$total_carrinho = 0;
if ($total_reg == 0) {
    echo "<script>window.location='index'</script>";
} else {
    for ($i = 0; $i < $total_reg; $i++) {
        $id                     = $res[$i]['id'];
        $total_item             = $res[$i]['total_item'];
        $id_produto             = $res[$i]['id_produto'];
        $valor_total_carrinho   += $total_item;
        $valor_total_carrinhoF  = 'R$' . number_format($valor_total_carrinho, 2, ',', '.');
    }
}

$total_pagar = $valor_total_carrinho + $valor_entrega;
$total_pagarF = 'R$ ' . number_format($total_pagar, 2, ',', '.');

?>

<body>
    <div class="main-container">
        <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
            <div class="container-fluid">
                <a class="navbar-brand" href="index">
                    <img src="img/<?= htmlspecialchars($logotipo) ?>" width="30" height="30" alt="Logo" class="d-inline-block align-text-top">
                    Finalizar Pedido
                </a>
                <?php require_once("./icone-carrinho.php"); ?>
            </div>
        </nav>
        <div class="accordion" id="accordionExample" style="margin-top: 55px">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseOne"
                        aria-expanded="true"
                        aria-controls="collapseOne">
                        1 - IDENTIFICAÇÃO
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                    <div class="accordion-body centralizar">
                        <i class="bi bi-person-fill fs-1"></i>
                        <div class="nome_user"><?php echo $nome_cliente ?></div>
                        <div class="telefone_user"><?php echo $telefone_cliente ?></div>
                        <hr>
                        <div><b>Seus Dados estão corretos?</b></div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <a href="index" class="btn btn-danger w-100">NÃO</a>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-success w-100" data-bs-toggle="collapse" data-bs-target="#collapseTwo">SIM</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo"
                        aria-expanded="false"
                        id="colapse-2"
                        aria-controls="collapseTwo">
                        2 - MODO DE ENTREGA
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <ul class="list-group">
                            <li class="list-group-item" onclick="retirar()">
                                <div class="form-check">
                                    <input onchange="retirar()" class="form-check-input" type="radio" name="modo_entrega" id="radio_retirar" value="retirar">
                                    <label class="form-check-label" for="radio_retirar">Retirar no Local</label>
                                </div>
                            </li>
                            <li class="list-group-item" onclick="local()">
                                <div class="form-check">
                                    <input onchange="local()" class="form-check-input" type="radio" name="modo_entrega" id="radio_local" value="local">
                                    <label class="form-check-label" for="radio_local">Consumir no Local</label>
                                </div>
                            </li>
                            <li class="list-group-item" onclick="entrega()">
                                <div class="form-check">
                                    <input onchange="entrega()" class="form-check-input" type="radio" name="modo_entrega" id="radio_entrega" value="entrega">
                                    <label class="form-check-label" for="radio_entrega">Entrega Delivery</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseThree"
                        aria-expanded="false"
                        aria-controls="collapseThree"
                        id="colapse-3">
                        3 - ENDEREÇO
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div id="area-retirada">
                            <a href="" class="area-retirada" data-bs-toggle="collapse" data-bs-target="#collapse4" style="text-decoration: none; color:#000">
                                <span id="consumir-local"><strong>Nossa unidade:</strong></span><br>
                                <?php echo $rua_sistema ?>, <?php echo $numero_sistema ?> - <?php echo $bairro_sistema ?><br>
                                <?php echo $cidade_sistema ?> - <?php echo $estado_sistema ?> | <?php echo $cep_sistema ?>
                                <i class="bi bi-check-lg"></i>
                            </a>
                        </div>
                        <div id="area-endereco">
                            <div class="row">
                                <div class="col-4 col-md-4">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="cep"
                                            id="cep-finalizar"
                                            placeholder="CEP"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-8 col-md-3">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="rua"
                                            id="rua"
                                            placeholder="Rua"
                                            readonly
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-3 col-md-1">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="numero"
                                            id="numero"
                                            placeholder="Nº"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="cidade"
                                            id="cidade"
                                            placeholder="Cidade"
                                            readonly
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-3 col-md-1">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="estado"
                                            id="estado"
                                            placeholder="Estado"
                                            readonly
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-6">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="bairro"
                                            id="bairro_nome"
                                            placeholder="Bairro"
                                            value="<?php echo htmlspecialchars($nome_bairro); ?>">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-3">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="valor_entrega"
                                            id="valor_entrega"
                                            placeholder="Taxa"
                                            value="<?php echo $valor_entregaF; ?>"
                                            readonly
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="centralizar"><a href="#" data-bs-toggle="collapse" data-bs-target="#collapse4">Avançar para Pagamento</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading4">
                    <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse4"
                        aria-expanded="false"
                        id="colapse-4"
                        aria-controls="collapse4">
                        4 - PAGAMENTO
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-3 form-check">
                                <input onchange="pix()" class="form-check-input" type="radio" name="pagamento" id="radio_pix" value="pix">
                                <label class="form-check-label" for="radio_pix">PIX</label>
                            </div>
                            <div class="col-3 form-check">
                                <input onchange="dinheiro()" class="form-check-input" type="radio" name="pagamento" id="radio_dinheiro" value="dinheiro">
                                <label class="form-check-label" for="radio_dinheiro">Dinheiro</label>
                            </div>
                            <div class="col-3 form-check">
                                <input onchange="credito()" class="form-check-input" type="radio" name="pagamento" id="radio_credito" value="credito">
                                <label class="form-check-label" for="radio_credito">Crédito</label>
                            </div>
                            <div class="col-3 form-check">
                                <input onchange="debito()" class="form-check-input" type="radio" name="pagamento" id="radio_debito" value="debito">
                                <label class="form-check-label" for="radio_debito">Débito</label>
                            </div>
                        </div>
                        <div id="pagar_pix" class="mt-6">
                            <b>Pagar com Pix </b><br>
                            <strong>Chave</strong> <?php echo $tipo_chave ?> : <?php echo $chave_pix ?><br>
                            <p class="pequeno">
                                Ao efetuar o pagamento nos encaminhar o comprovante no whatsapp
                                <a href="https://api.whatsapp.com/send?phone=<?php echo $telefone_url ?>; ?>
                                &text=Segue%20o%20comprovante%20do%20pagamento%20do%20pedido%20nº%20%2001234"
                                    target="_blank"
                                    class="link-neutro"><br>
                                    <i class="bi bi-whatsapp text-success"></i>&nbsp;<?php echo $telefone_sistema ?>
                                </a>
                            </p>
                        </div>
                        <div id="pagar_dinheiro" class="mt-6">
                            <b>Dinheiro na Entrega </b><br>
                            <div class="row">
                                <div class="col-5">
                                    <p>Troco? Para... R$ </p>
                                </div>
                                <div class="col-7" class="mt-6">
                                    <div class="group">
                                        <input type="text"
                                            class="entra placetroco"
                                            name="troco"
                                            id="troco"
                                            placeholder="Vou precisar de troco para... R$ ?"
                                            onblur="formatarMoedaReal(this)"
                                            onfocus="removerFormatacao(this)"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo">Vou precisar de troco para... R$ ?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="pagar_credito" class="mt-6">
                            <b>Pagar com Cartão de Crédito </b><br>
                            <p class="pequeno">O Pagamento será efetuado no ato da entrega com cartão de crédito</p>
                        </div>
                        <div id="pagar_debito" class="mt-6">
                            <b>Pagar com Cartão de Débito </b><br>
                            <p class="pequeno">O Pagamento será efetuado no ato da entrega com cartão de débito</p>
                        </div>
                    </div>

                    <div class="grupo mt-4 mx-5" id="area-obs">
                        <input type="text"
                            class="entra"
                            name="obs"
                            id="obs"
                            placeholder="Observações do Pedido">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="entrega">
    <input type="hidden" id="pagamento">
    <div class="centralizar" id="area-taxa">
        <span>Previsão Entrega: <?php echo $previsao_entrega ?> minutos.</span><br>
    </div>
    <div class="direita">
        <strong>TOTAL ITEM - <span id="total-item"><?php echo $valor_total_carrinhoF ?></strong></span><br>
        <div id="taxa-entrega">
            <span class="taxa-entrega">Valor Entrega - <?php echo $valor_entregaF ?></span><br>
            <strong>TOTAL À PAGAR - <span id="total-pagar"><?php echo $total_pagarF ?></span></strong><br>
        </div>
    </div>
    <div class="d-grid gap-2 mt-4 abaixo">
        <a href='#' onclick="finalizarPedido()" class="btn btn-primary botao-carrinho">Concluir Pedido</a>
    </div>
    <?php require_once("./rodape.php"); ?>

    <script>
        $(document).ready(function() {
            $('#bairro_nome').on('input', function() {
                var nomeBairro = $(this).val().trim();
                if (nomeBairro === "") return;

                $.ajax({
                    url: 'js/ajax/buscar-taxa-entrega.php',
                    method: 'POST',
                    data: {
                        bairro: nomeBairro
                    },
                    dataType: 'text',
                    success: function(response) {
                        if (response.trim() === "NAO_ENTREGAMOS") {
                            alert("Ainda não entregamos nessa localidade. Logo estaremos aí!");
                            document.getElementById('valor_entrega').value = '';
                            document.querySelector('.taxa-entrega').textContent = 'Valor Entrega - R$ 0,00';
                            document.getElementById('total-pagar').textContent = 'R$ <?php echo number_format($valor_total_carrinho, 2, ',', '.'); ?>';
                            $('#cep-finalizar').val('');
                            $('#cep-finalizar').focus();
                            return;
                        }
                        var valorEntrega = parseFloat(response.trim()) || 0;
                        var totalItens = <?php echo $valor_total_carrinho; ?>;
                        var totalPagar = totalItens + valorEntrega;

                        function formatarReal(valor) {
                            return 'R$ ' + valor.toFixed(2).replace('.', ',');
                        }

                        document.getElementById('valor_entrega').value = formatarReal(valorEntrega);
                        document.querySelector('.taxa-entrega').textContent = 'Valor Entrega - ' + formatarReal(valorEntrega);
                        document.getElementById('total-pagar').textContent = formatarReal(totalPagar);
                    }
                });
            });

            document.getElementById('area-endereco').style.display = "none";
            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
            document.getElementById('area-obs').style.display = "none";
            document.getElementById('taxa-entrega').style.display = "none";
            document.getElementById('area-taxa').style.display = "none";

            if ($('#rua').val() === '') $('#rua').val('<?php echo addslashes($rua_cliente); ?>');
            if ($('#numero').val() === '') $('#numero').val('<?php echo addslashes($numero_cliente); ?>');
            if ($('#cidade').val() === '') $('#cidade').val('<?php echo addslashes($cidade_cliente); ?>');
            if ($('#estado').val() === '') $('#estado').val('<?php echo addslashes($estado_cliente); ?>');
            if ($('#cep-finalizar').val() === '') $('#cep-finalizar').val('<?php echo addslashes($cep_cliente); ?>');
            if ($('#bairro').val() === '') $('#bairro').val('<?php echo addslashes($id_bairro_cliente); ?>');

            $('#cep-finalizar').on('blur', function() {
                setTimeout(function() {
                    var bairro = $('#bairro_nome').val().trim();
                    if (bairro !== "") {
                        $('#bairro_nome').trigger('input');
                    }
                }, 500);
            });
        });

        function retirar() {
            document.getElementById('radio_retirar').checked = true;
            $('#colapse-3').text('3 - ENDEREÇO DE RETIRADA');
            $('#colapse-3').click();
            $('#entrega').val('Retirar');
            $('#consumir-local').html('<strong>Endereço de retirada:</strong>')

            document.getElementById('area-retirada').style.display = "block";
            document.getElementById('area-endereco').style.display = "none";
            document.getElementById('taxa-entrega').style.display = "none";
            document.getElementById('area-taxa').style.display = "none";
        }

        function local() {
            document.getElementById('radio_local').checked = true;
            $('#colapse-3').text('3 - NOSSO ENDEREÇO');
            $('#colapse-3').click();
            $('#entrega').val('Consumir no Local');
            $('#consumir-local').html('<strong>Endereço da nossa unidade:</strong>')

            document.getElementById('area-retirada').style.display = "block";
            document.getElementById('area-endereco').style.display = "none";
            document.getElementById('taxa-entrega').style.display = "none";
            document.getElementById('area-taxa').style.display = "none";
        }

        function entrega() {
            document.getElementById('radio_entrega').checked = true;
            $('#colapse-3').text('3 - ENDEREÇO DE ENTREGA');
            $('#colapse-3').click();
            $('#entrega').val('Delivery');

            document.getElementById('area-retirada').style.display = "none";
            document.getElementById('area-endereco').style.display = "block";
            document.getElementById('taxa-entrega').style.display = "block";
            document.getElementById('area-taxa').style.display = "block";
        }

        function pix() {
            $('#pagamento').val('PIX');

            document.getElementById('pagar_pix').style.display = "block";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
            document.getElementById('area-obs').style.display = "block";
        }

        function dinheiro() {
            $('#pagamento').val('Dinheiro');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "block";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
            document.getElementById('area-obs').style.display = "block";
        }

        function credito() {
            $('#pagamento').val('Crédito');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "block";
            document.getElementById('pagar_debito').style.display = "none";
            document.getElementById('area-obs').style.display = "block";
        }

        function debito() {
            $('#pagamento').val('Débito');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "block";
            document.getElementById('area-obs').style.display = "block";
        }

        function formatarMoedaReal(input) {
            // Remove tudo que não é número
            let valor = input.value.replace(/\D/g, '');

            if (valor === '') {
                input.value = '';
                return;
            }

            // Converte para número
            let numero = parseInt(valor);

            if (!isNaN(numero)) {
                // Formata como moeda brasileira
                input.value = numero.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }
        }

        function removerFormatacao(input) {
            input.value = input.value.replace(/\D/g, '');
        }

        function finalizarPedido() {
            var entrega = $('#entrega').val();
            var rua = $('#rua').val();
            var numero = $('#numero').val();
            var cidade = $('#cidade').val();
            var estado = $('#estado').val();
            var cep = $('#cep-finalizar').val();
            var bairro = $('#bairro_nome').val();
            var pagamento = $('#pagamento').val();
            var troco = $('#troco').val();
            var total_compra = <?php echo $valor_total_carrinho; ?>;
            var obs = $('#obs').val();

            if (entrega == "") {
                alert('Selecione uma forma de entrega!');
                $('#colapse-2').click();
                return;
            }

            if (entrega == "Delivery") {
                var enderecoAberto = $('#collapseThree').hasClass('show');

                if (cep.trim() === "") {
                    alert('Preencha o campo cep para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#cep-finalizar').focus(), 300);
                    return;
                }
                if (rua.trim() === "") {
                    alert('Preencha o nome da rua para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#rua').focus(), 300);
                    return;
                }
                if (numero.trim() === "") {
                    alert('Preencha o nº da casa para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#numero').focus(), 300);
                    return;
                }
                if (cidade.trim() === "") {
                    alert('Preencha o campo cidade para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#cidade').focus(), 300);
                    return;
                }
                if (estado.trim() === "") {
                    alert('Preencha o campo estado para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#estado').focus(), 300);
                    return;
                }
                if (bairro == "0") {
                    alert('Preencha o campo bairro para a entrega!');
                    if (!enderecoAberto) $('#colapse-3').click();
                    setTimeout(() => $('#bairro').focus(), 300);
                    return;
                }
            }

            if (pagamento == "") {
                alert('Selecione uma forma de pagamento!');
                $('#colapse-4').click();
                return;
            }
            if (pagamento == "Dinheiro" && troco === "") {
                alert('Digite o total enviado, para o troco!');
                $('#troco').focus();
                return;
            }
            var trocoNumerico = parseFloat(troco.replace(/\D/g, '')) / 100;
            if (trocoNumerico < total_compra) {
                alert('Digite um valor acima do total da compra!');
                $('#troco').val("");
                $('#troco').focus();
                alert(trocoNumerico);
                return;
            }

            $.ajax({
                url: 'js/ajax/inserir-pedido.php',
                method: 'POST',
                data: {
                    entrega,
                    rua,
                    numero,
                    cidade,
                    estado,
                    cep,
                    bairro,
                    pagamento,
                    trocoNumerico,
                    obs,
                },
                dataType: 'html',

                success: function(result) {
                    alert("Pedido finalizado!!");
                    window.location='index';
                },
            });
        }
    </script>
</body>

</html>