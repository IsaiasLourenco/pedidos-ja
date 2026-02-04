<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('../../sistema/conexao.php');

$telefone = $_POST['telefone'];
$nome = $_POST['nome'];
$quantidade = $_POST['quantidade'];
$total_item = $_POST['total_item'];
$produto = $_POST['produto'];
$obs = $_POST['obs'];

if (empty($_SESSION['sessao_usuario'])) {
    $_SESSION['sessao_usuario'] = uniqid('sess_', true);
}
$sessao = $_SESSION['sessao_usuario'];

// Verifica cliente
$query = $pdo->query("SELECT * FROM cliente WHERE telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    $id_cliente = $res[0]['id'];
} else {
    $query = $pdo->prepare("INSERT INTO cliente SET nome = :nome, telefone = :telefone, data_cad = CURDATE()");
    $query->bindValue(":nome", $nome);
    $query->bindValue(":telefone", $telefone);
    $query->execute();
    $id_cliente = $pdo->lastInsertId();
}

// Insere no carrinho (TUDO com placeholders)
$query_car = $pdo->prepare("INSERT INTO carrinho SET  
    id_cliente = :id_cliente,
    id_produto = :id_produto,
    quantidade = :quantidade,
    total_item = :total_item,
    sessao = :sessao,
    pedido = '0',
    observacoes = :observacoes");

$query_car->bindValue(":id_cliente", $id_cliente);
$query_car->bindValue(":id_produto", $produto);
$query_car->bindValue(":quantidade", $quantidade);
$query_car->bindValue(":total_item", $total_item);
$query_car->bindValue(":sessao", $sessao);
$query_car->bindValue(":observacoes", $obs);
$query_car->execute();

$id_carrinho = $pdo->lastInsertId(); // ← AGORA EXISTE!

// Atualiza carrinho_temp
$pdo->query("UPDATE carrinho_temp SET carrinho = '$id_carrinho' WHERE sessao = '$sessao' AND carrinho = '0'");

echo 'Inserido com sucesso!';
?>