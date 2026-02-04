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

$total_carrinho = 0;
if ($total_reg == 0) {
    echo "<script>window.location='index'</script>";
} else {
    for ($i = 0; $i < $total_reg; $i++) {
        $id                     = $res[$i]['id'];
        $total_item             = $res[$i]['total_item'];
        $id_produto             = $res[$i]['id_produto'];
        $valor_total_carrinho   += $total_item;
        $valor_total_carrinhoF  = 'R$' . number_format($valor_total_carrinho, 2, ',', '.');;
    }
}

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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
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
                                <div class="col-4">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="rua"
                                            id="rua"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo"><?php echo $rua_cliente ?></label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="numero"
                                            id="numero"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo"><?php echo $numero_cliente ?></label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="cidade"
                                            id="cidade"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo"><?php echo $cidade_cliente ?></label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="estado"
                                            id="estado"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo"><?php echo $estado_cliente ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-5">
                                    <div class="grupo">
                                        <input type="text"
                                            class="entra"
                                            name="cep"
                                            id="cep"
                                            required>
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="rotulo"><?php echo $cep_cliente ?></label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div class="grupo">
                                        <select class="entra" name="bairro" id="bairro" required style="background: transparent;">
                                            <option value="0">Selecione um Bairro</option>
                                            <?php
                                            $queryBairro = $pdo->query("SELECT * FROM bairros ORDER BY id ASC");
                                            $resBairro = $queryBairro->fetchAll(PDO::FETCH_ASSOC);
                                            $total_regBairro = count($resBairro);
                                            if ($total_regBairro > 0) {
                                                for ($i = 0; $i < $total_regBairro; $i++) {
                                                    $valorF = 'R$ ' . number_format($resBairro[$i]['valor'], 2, ',', '.');
                                                    if ($resBairro[$i]['id'] == $id_bairro_cliente) {
                                                        $classe_bairro = 'selected';
                                                    } else {
                                                        $classe_bairro = '';
                                                    }
                                                    echo '<option value="' . $resBairro[$i]['nome'] . '" ' . $classe_bairro . '>' . $resBairro[$i]['nome'] . ' - ' . $valorF . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
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
                            <small>
                                Ao efetuar o pagamento nos encaminhar o comprovante no whatsapp
                                <a href="https://api.whatsapp.com/send?phone=<?php echo $telefone_url ?>; ?>
                                &text=Segue%20o%20comprovante%20do%20pagamento%20do%20pedido%20nº%20%2001234"
                                    target="_blank"
                                    class="link-neutro"><br>
                                    <i class="bi bi-whatsapp text-success"></i>&nbsp;<?php echo $telefone_sistema ?>
                                </a>
                            </small>
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
                                                name="numero"
                                                id="troco"
                                                placeholder="Vou precisar de troco para... R$ ?"
                                                oninput="formatarMoedaReal(this)"
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
                            <small>O Pagamento será efetuado no ato da entrega com cartão de crédito</small>
                        </div>
                        <div id="pagar_debito" class="mt-6">
                            <b>Pagar com Cartão de Débito </b><br>
                            <small>O Pagamento será efetuado no ato da entrega com cartão de débito</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="text" id="entrega">
    <input type="text" id="pagamento">
    <div class="centralizar">
        <span>Previsão Entrega: <?php echo $previsao_entrega ?> minutos.</span><br>
    </div>
    <div class="direita">
        <strong>TOTAL À PAGAR - <p id="total-carrinho-finalizar"><?php echo $valor_total_carrinhoF?></strong></p>
    </div>
    <div class="d-grid gap-2 mt-4 abaixo">
        <a href='#' onclick="finalizarPedido()" class="btn btn-primary botao-carrinho">Concluir Pedido</a>
    </div>
    <?php require_once("./rodape.php"); ?>

    <script>
        $(document).ready(function() {
            document.getElementById('area-endereco').style.display = "none";
            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
        });

        function retirar() {
            document.getElementById('radio_retirar').checked = true;
            $('#colapse-3').text('3 - ENDEREÇO DE RETIRADA');
            $('#colapse-3').click();
            $('#entrega').val('Retirar');
            $('#consumir-local').html('<strong>Endereço de retirada:</strong>')

            document.getElementById('area-retirada').style.display = "block";
            document.getElementById('area-endereco').style.display = "none";
        }

        function local() {
            document.getElementById('radio_local').checked = true;
            $('#colapse-3').text('3 - NOSSO ENDEREÇO');
            $('#colapse-3').click();
            $('#entrega').val('Consumir no Local');
            $('#consumir-local').html('<strong>Endereço da nossa unidade:</strong>')

            document.getElementById('area-retirada').style.display = "block";
            document.getElementById('area-endereco').style.display = "none";
        }

        function entrega() {
            document.getElementById('radio_entrega').checked = true;
            $('#colapse-3').text('3 - ENDEREÇO DE ENTREGA');
            $('#colapse-3').click();
            $('#entrega').val('Delivery');

            document.getElementById('area-retirada').style.display = "none";
            document.getElementById('area-endereco').style.display = "block";
        }

        function pix() {
            $('#pagamento').val('PIX');

            document.getElementById('pagar_pix').style.display = "block";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
        }

        function dinheiro() {
            $('#pagamento').val('Dinheiro');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "block";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "none";
        }

        function credito() {
            $('#pagamento').val('Crédito');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "block";
            document.getElementById('pagar_debito').style.display = "none";
        }

        function debito() {
            $('#pagamento').val('Débito');

            document.getElementById('pagar_pix').style.display = "none";
            document.getElementById('pagar_dinheiro').style.display = "none";
            document.getElementById('pagar_credito').style.display = "none";
            document.getElementById('pagar_debito').style.display = "block";
        }

        function formatarMoedaReal(input) {
            // Remove tudo que não é número
            let valor = input.value.replace(/\D/g, '');

            // Converte para número (centavos)
            valor = valor / 100;

            // Formata como Real Brasileiro
            if (!isNaN(valor)) {
                input.value = valor.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }
        }

        function removerFormatacao(input) {
            // Remove a formatação para facilitar edição
            input.value = input.value.replace(/\D/g, '');
        }

    </script>
</body>

</html>