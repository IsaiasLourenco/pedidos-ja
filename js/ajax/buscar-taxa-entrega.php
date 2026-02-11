<?php
require_once("../../sistema/conexao.php");

$nome_bairro = $_POST['bairro'];

// Verifica se já existe (ativo ou pendente)
$query = $pdo->prepare("SELECT * FROM bairros WHERE nome = ?");
$query->execute([$nome_bairro]);
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
    if ($res[0]['status'] == 'ativo') {
        echo $res[0]['valor'];
    } else {
        echo "NAO_ENTREGAMOS";
    }
} else {
    $pdo->prepare("INSERT INTO bairros (nome, valor, status) VALUES (?, 0.00, 'pendente')")
         ->execute([$nome_bairro]);
    echo "NAO_ENTREGAMOS";
}
?>