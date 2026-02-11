<?php
session_start();
require_once('../../sistema/conexao.php');

$modo_entrega   = $_POST['entrega'];
$rua            = $_POST['rua'];
$numero         = $_POST['numero'];
$cidade         = $_POST['cidade'];
$estado         = $_POST['estado'];
$cep            = $_POST['cep'];
$bairro         = $_POST['bairro'];
$modo_pagamento = $_POST['pagamento'];
$obs            = $_POST['obs'];
$sessao         = $_SESSION['sessao_usuario'];
$valor_pago     = $_POST['trocoNumerico'];

$valor_entrega = 0;
$id_Bairro = null;

if ($modo_entrega === 'Delivery' && !empty($bairro)) {
    $queryBairro = $pdo->query("SELECT * FROM bairros WHERE nome = '$bairro' AND status = 'ativo'");
    $resBairro = $queryBairro->fetchAll(PDO::FETCH_ASSOC);
    $id_Bairro      = $resBairro[0]['id'];
    $valor_entrega  = $resBairro[0]['valor'];
    $nome_bairro    = $resBairro[0]['nome'];
} else {
    $valor_entrega = 0;
}

$total_carrinho = 0;
$query = $pdo->query("SELECT * FROM carrinho WHERE sessao = '$sessao'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg  = count($res);
$id_cliente = $res[0]['id_cliente'];

for ($i = 0; $i < $total_reg; $i++) {
    $id_carrinho            = $res[$i]['id'];
    $total_item             = $res[$i]['total_item'];
    $id_produto             = $res[$i]['id_produto'];
    $valor_total_carrinho   += $total_item;
}

$total_pedido = $valor_total_carrinho + $valor_entrega;

if ($modo_pagamento == 'Dinheiro') {
    $troco = $valor_pago - $total_pedido;
} else {
    $troco = 0; // não há troco
}

$query_car = $pdo->prepare("INSERT INTO vendas   SET cliente     = :id_cliente,
                                                     valor_compra   = :valor_compra,
                                                     valor_pago     = :valor_pago,
                                                     troco          = :troco,
                                                     data_pagamento = curDate(),
                                                     hora_pagamento = curTime(),
                                                     status_venda   = 'Iniciado',
                                                     pago           = 'Não',
                                                     obs            = :obs,
                                                     valor_entrega  = :valor_entrega,
                                                     tipo_pagamento = :tipo_pagamento,
                                                     tipo_pedido    = :tipo_pedido,
                                                     usuario_baixa  = '0'");

$query_car->bindValue(":id_cliente", $id_cliente);
$query_car->bindValue(":valor_compra", $valor_total_carrinho);
$query_car->bindValue(":valor_pago", $valor_pago);
$query_car->bindValue(":troco", $troco);
$query_car->bindValue(":obs", $obs);
$query_car->bindValue(":valor_entrega", $valor_entrega);
$query_car->bindValue(":tipo_pagamento", $modo_pagamento);
$query_car->bindValue(":tipo_pedido", $modo_entrega);
$query_car->execute();

$id_carrinho = $pdo->lastInsertId();

$query_venda_endereco = $pdo->prepare("INSERT INTO vendas_endereco SET  venda_id    = :venda_id, 
                                                                        bairro_id   = :bairro_id, 
                                                                        rua         = :rua, 
                                                                        numero      = :numero, 
                                                                        cidade      = :cidade,
                                                                        estado      = :estado,
                                                                        cep         = :cep");
                                                                        
$query_venda_endereco->bindValue(":venda_id", $id_carrinho);
$query_venda_endereco->bindValue(":bairro_id", $id_Bairro);
$query_venda_endereco->bindValue(":rua", $rua);
$query_venda_endereco->bindValue(":numero", $numero);
$query_venda_endereco->bindValue(":estado", $estado);
$query_venda_endereco->bindValue(":cidade", $cidade);
$query_venda_endereco->bindValue(":cep", $cep);
$query_venda_endereco->execute();

$pdo->query("UPDATE carrinho SET pedido = '$id_carrinho' WHERE sessao = '$sessao' AND pedido = '0'");

$_SESSION['sessao_usuario'] = "";

echo 'Inserido com sucesso!';
