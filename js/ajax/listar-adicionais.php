    <?php
    session_start();
    require_once("../../sistema/conexao.php");

    $id_produto = $_POST['id_produto'] ?? null;
    $valor_item = $_POST['valor_item'] ?? 0;

    if (!$id_produto) {
        echo 'Não achamos o id do seu produto!!';
        exit;
    }

    // Garante que é um número inteiro (segurança)
    $id_produto = (int)$id_produto;

    $sessao = $_SESSION['sessao_usuario'] ?? '';

    $query              = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
    $res                = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg          = count($res);
    if ($total_reg > 0) {
        $id_produto             = $res[0]['id'];
    }

    $queryAd = $pdo->query("SELECT * FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim'");
    $resAd = $queryAd->fetchAll(PDO::FETCH_ASSOC);
    $total_regAd = count($resAd);
    if ($total_regAd > 0) {
        echo '<div class="fw-bold titulo-item mt-3">Adicionais</div>';
        for ($i = 0; $i < $total_regAd; $i++) {
            $id_adicional       = $resAd[$i]['id'];
            $nome_adicional     = $resAd[$i]['nome'];
            $valor_adicional    = $resAd[$i]['valor'];
            $valorAdFormatado  = 'R$ ' . number_format($valor_adicional, 2, ',', '.');
            $queryTemp = $pdo->query("SELECT * FROM carrinho_temp WHERE sessao = '$sessao' AND id_item  = '$id_adicional'
                                                                                           AND tabela   = 'adicionais'
                                                                                           AND carrinho = '0'");
            $resTemp = $queryTemp->fetchAll(PDO::FETCH_ASSOC);
            $total_regTemp = count($resTemp);
            if ($total_regTemp > 0) {
                $icone = 'bi-check-square-fill';
                $titulo_link = 'Remover Adicional';
                $acao = 'Não';
                $valor_item += $valor_adicional;
            } else {
                $icone = 'bi-square-fill';
                $titulo_link = 'Escolher Adicional';
                $acao = 'Sim';
            }
echo <<<HTML
                <a href="#" onclick="adicionar('{$id_adicional}', '{$acao}')" class="link-neutro" title={$titulo_link}>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="descricao-item"> {$nome_adicional} -
                                <span class="valor-item">
                                    {$valorAdFormatado}
                                </span>
                            </div>
                        </div>
                        <i class="bi <?php {$icone} text-primary"></i>
                    </li>
                </a>
HTML;
}

}