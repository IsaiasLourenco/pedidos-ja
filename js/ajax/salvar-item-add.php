<?php
require_once('../../sistema/conexao.php');

$id_carrinho = $_POST['id_carrinho'];
$ingredientes_remover = $_POST['ingredientes_remover'] ?? [];
$adicionais_adicionar = $_POST['adicionais_adicionar'] ?? [];

try {
    // 1. Obter dados atuais do item
    $queryItem = $pdo->query("SELECT id_produto, quantidade, total_item FROM carrinho WHERE id = '$id_carrinho'");
    $item = $queryItem->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        echo "Item não encontrado!";
        exit;
    }
    $id_produto = $item['id_produto'];
    $quantidade = (int)$item['quantidade'];
    $total_atual = floatval($item['total_item']);

    // 2. Calcular valor dos adicionais ATUAIS (os que estão no carrinho_temp)
    $valor_adicionais_atuais = 0;
    $queryTemp = $pdo->query("SELECT a.valor FROM carrinho_temp ct JOIN adicionais a ON ct.id_item = a.id WHERE ct.carrinho = '$id_carrinho' AND ct.tabela = 'adicionais'");
    while ($row = $queryTemp->fetch(PDO::FETCH_ASSOC)) {
        $valor_adicionais_atuais += floatval($row['valor']);
    }

    // 3. Valor base real = total atual - adicionais atuais
    $valor_base = $total_atual - $valor_adicionais_atuais;

    // 4. Calcular valor dos NOVOS adicionais
    $valor_adicionais_novos = 0;
    if (!empty($adicionais_adicionar)) {
        $ids = implode(',', array_map('intval', $adicionais_adicionar));
        $queryAdc = $pdo->query("SELECT SUM(valor) as total FROM adicionais WHERE id IN ($ids) AND produto = '$id_produto'");
        $soma = $queryAdc->fetch(PDO::FETCH_ASSOC);
        $valor_adicionais_novos = floatval($soma['total'] ?? 0);
    }

    // 5. Novo total
    $novo_total_item = $valor_base + $valor_adicionais_novos;

    // 6. Atualizar carrinho_temp
    $pdo->query("DELETE FROM carrinho_temp WHERE carrinho = '$id_carrinho'");

    foreach ($ingredientes_remover as $id_ing) {
        $pdo->query("INSERT INTO carrinho_temp (carrinho, id_item, tabela) VALUES ('$id_carrinho', '$id_ing', 'ingredientes')");
    }

    foreach ($adicionais_adicionar as $id_adc) {
        $pdo->query("INSERT INTO carrinho_temp (carrinho, id_item, tabela) VALUES ('$id_carrinho', '$id_adc', 'adicionais')");
    }

    // 7. Atualizar total no carrinho
    $pdo->query("UPDATE carrinho SET total_item = '$novo_total_item' WHERE id = '$id_carrinho'");

    echo "Salvo com sucesso!";
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
?>