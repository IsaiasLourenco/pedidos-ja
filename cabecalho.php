<?php
require_once("./sistema/conexao.php");

$sql = $pdo->query("SELECT * FROM config");
$config = $sql->fetchAll(PDO::FETCH_ASSOC);
if (!$config) {
    die("Não foi possível reconhecer as informações da tabela configurações!!");
} else {
    $nome_sistema       = $config[0]['nome_sistema'];
    $email_sistema      = $config[0]['email_sistema'];
    $telefone_sistema   = $config[0]['telefone_sistema'];
    $telefone_fixo      = $config[0]['telefone_fixo'];
    $cnpj_sistema       = $config[0]['cnpj_sistema'];
    $cep_sistema        = $config[0]['cep_sistema'];
    $rua_sistema        = $config[0]['rua_sistema'];
    $numero_sistema     = $config[0]['numero_sistema'];
    $bairro_sistema     = $config[0]['bairro_sistema'];
    $cidade_sistema     = $config[0]['cidade_sistema'];
    $estado_sistema     = $config[0]['estado_sistema'];
    $instagram_sistema  = $config[0]['instagram_sistema'];
    $tipo_relatorio     = $config[0]['tipo_relatorio'];
    $cards              = $config[0]['cards'];
    $pedidos            = $config[0]['pedidos'];
    $desenvolvedor      = $config[0]['desenvolvedor'];
    $site_dev           = $config[0]['site_dev'];
    $previsao_entrega   = $config[0]['previsao_entrega'];
    $aberto             = $config[0]['estabelecimento_aberto'];
    $abertura           = $config[0]['abertura'];
    $fechamento         = $config[0]['fechamento'];
    $texto_fecha_dia     = $config[0]['texto_fecha_dia'];
    $texto_fecha_hora   = $config[0]['texto_fecha_hora'];
    $texto_fecha_urg    = $config[0]['texto_fecha_urg'];
    $logotipo           = $config[0]['logotipo'];
    $icone              = $config[0]['icone'];
    $logo_rel           = $config[0]['logo_rel'];
    $url_sistema        = $config[0]['url_sistema'];
    $tempo_atualizacao  = $config[0]['tempo_atualizacao'];
    $tipo_chave         = $config[0]['tipo_chave'];
    $chave_pix          = $config[0]['chave_pix'];
}
// | ENDEREÇO FORMATADO
$endereco_sistema = trim(
    $rua_sistema . ', ' .
        $numero_sistema . ' - ' .
        $bairro_sistema . ' - ' .
        $cidade_sistema . '/' .
        $estado_sistema
);

// | WHATSAPP
$telefone_url = '55' . preg_replace('/\D/', '', $telefone_sistema);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title><?= htmlspecialchars($nome_sistema) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">.
    <meta name="keywords" content="Delivery Interativo para restaurantes, lanchonetes e pizzarias">
    <meta name="author" content="Vetor256.">
    <!-- Favicon padrão (obrigatório para evitar fallback para /favicon.ico) -->
    <link rel="icon" href="/delivery/img/<?= htmlspecialchars($icone) ?>?v=1" type="image/png">
    <!-- Favicon alternativo para compatibilidade -->
    <link rel="shortcut icon" href="/delivery/img/<?= htmlspecialchars($icone) ?>" type="image/x-icon">
    <!-- CSS BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet" i
        ntegrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
    <!-- JS BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
    </script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Folha de estilo -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRQh1X+KwBkK9PqGz5ZlD9aIjS1UyHsFg==" crossorigin="anonymous"></script>

<body class="fundoCinza">