<?php
require_once('../../sistema/conexao.php');

if ($_POST['id']) {
    $id = $_POST['id'];

    try {
        // 1. Excluir registros em carrinho_temp relacionados
        $pdo->query("DELETE FROM carrinho_temp WHERE carrinho = '$id'");

        // 2. Excluir o item do carrinho
        $pdo->query("DELETE FROM carrinho WHERE id = '$id'");

        echo "Excluído com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao excluir: " . $e->getMessage();
    }
} else {
    echo "ID não informado!";
}
?>
