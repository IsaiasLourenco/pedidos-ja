<?php 
require_once('../../sistema/conexao.php');
$id     = $_POST['id'];
$obs   = $_POST['obs'];

$query = $pdo->prepare("UPDATE carrinho SET observacoes = :obs WHERE id = '$id'");
$query->bindValue(":obs", "$obs");
$query->execute();

echo 'Editado com sucesso!';
?>