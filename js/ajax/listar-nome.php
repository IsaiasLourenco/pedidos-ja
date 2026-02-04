<?php
require_once("../../sistema/conexao.php");
$tel = $_POST['tel'];

if (!$tel) {
    exit();
}

$query = $pdo->query("SELECT * FROM cliente WHERE telefone = '$tel'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    $id_cliente = $res[0]['id'];
    $nome = $res[0]['nome'];
    echo $nome;
}
?>