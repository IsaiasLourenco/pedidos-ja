<?php 
session_start();
require_once('../../sistema/conexao.php');
$id         = $_POST['id'];
$quantidade = $_POST['quantidade'];
$acao       = $_POST['acao'];

if (!isset($_SESSION['sessao_usuario']) || empty($_SESSION['sessao_usuario'])) {
    $_SESSION['sessao_usuario'] = uniqid('sess_', true);
}
$sessao = $_SESSION['sessao_usuario'];

if ($acao == 'menos') {
    $qtd = $quantidade - 1;
} else {
    $qtd = $quantidade + 1;
}

$queryCar = $pdo->query("SELECT * FROM carrinho WHERE id = '$id'");
$resCar = $queryCar->fetchAll(PDO::FETCH_ASSOC);
$total_item = $resCar[0]['total_item'];
$valor_unit = $total_item / $quantidade;
$novo_valor = $qtd * $valor_unit;

$pdo->query("UPDATE carrinho SET   quantidade = '$qtd',
                                   total_item = '$novo_valor'
                             WHERE id = '$id'");
echo 'Alterado com sucesso!';
?>