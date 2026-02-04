<?php
session_start();
require_once("../../sistema/conexao.php");

$id_produto = $_POST['id_produto'] ?? null;
$valor_base = $_POST['valor_base'] ?? 0;

if (!$id_produto) {
    echo '';
    exit;
}

$id_produto = (int)$id_produto;
$sessao = $_SESSION['sessao_usuario'] ?? '';

// Soma o valor dos adicionais que estão no carrinho
$total_adicionais = 0;
$queryAd = $pdo->query("SELECT id, valor FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim'");
$resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);

foreach ($resAd as $adicional) {
    $id_ad = $adicional['id'];
    $valor_ad = $adicional['valor'];
    $q = $pdo->query("SELECT COUNT(*) FROM carrinho_temp WHERE sessao = '$sessao' AND id_item = '$id_ad' AND tabela = 'adicionais'");
    if ($q->fetchColumn() > 0) {
        $total_adicionais += $valor_ad;
    }
}

$valor_total = $valor_base + $total_adicionais;
$valor_formatado = 'R$ ' . number_format($valor_total, 2, ',', '.');

echo <<<HTML
<div class="total mt-3">
    SUBTOTAL <strong>{$valor_formatado}</strong>
</div>
HTML;