<?php
session_start();
require_once("../../sistema/conexao.php");

$id_produto = $_POST['id_produto'] ?? null;

if (!$id_produto) {
    echo 'Não achamos o id do seu produto!!';
    exit;
}

// Garante que é um número inteiro (segurança)
$id_produto = (int)$id_produto;

$sessao = $_SESSION['sessao_usuario'] ?? '';
$queryIng = $pdo->query("SELECT * FROM ingredientes WHERE produto = '$id_produto' AND ativo = 'Sim'");
$resIng = $queryIng->fetchAll(PDO::FETCH_ASSOC);
$total_regIng = count($resIng);
if ($total_regIng > 0) {
    echo '<div class="fw-bold titulo-item mt-3">Ingredientes</div>';
    for ($iIng = 0; $iIng < $total_regIng; $iIng++) {
        $id_ingrediente       = $resIng[$iIng]['id'];
        $nome_ingrediente     = $resIng[$iIng]['nome'];
        $queryTempIng = $pdo->query("SELECT * FROM carrinho_temp WHERE sessao = '$sessao' AND id_item   = '$id_ingrediente'
                                                                                          AND tabela    = 'ingredientes'
                                                                                          AND carrinho  = '0'");
        $resTempIng = $queryTempIng->fetchAll(PDO::FETCH_ASSOC);
        $total_regTempIng = count($resTempIng);
        if ($total_regTempIng > 0) {
            $icone = 'bi-square-fill';
            $titulo_link = 'Escolher Ingrediente';
            $acao = 'Não';
        } else {
            $icone = 'bi-check-square-fill';
            $titulo_link = 'Remover Ingrediente';
            $acao = 'Sim';
        }
        echo <<<HTML
            <a href="#" onclick="adicionarIng('{$id_ingrediente}', '{$acao}')" class="link-neutro" title={$titulo_link}>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="descricao-item">
                            {$nome_ingrediente}
                        </div>
                    </div>
                    <i class="bi {$icone} text-primary"></i>
                </li>
            </a>
HTML;
    }
}
