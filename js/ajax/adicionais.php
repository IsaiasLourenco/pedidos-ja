<?php 
session_start();
require_once('../../sistema/conexao.php');
$id     = $_POST['id'];
$acao   = $_POST['acao'];

if (!isset($_SESSION['sessao_usuario']) || empty($_SESSION['sessao_usuario'])) {
    $_SESSION['sessao_usuario'] = uniqid('sess_', true);
}
$sessao = $_SESSION['sessao_usuario'];

if ($acao == 'Sim') {
    $pdo->query("INSERT INTO carrinho_temp SET sessao   = '$sessao',
                                               tabela   = 'adicionais',
                                               id_item  = '$id',
                                               carrinho = '0'");
} else {
    $pdo->query("DELETE FROM carrinho_temp WHERE sessao   = '$sessao'
                                            AND  tabela   = 'adicionais'
                                            AND  id_item  = '$id'
                                            AND  carrinho = '0'");
}
echo 'Alterado com sucesso';
?>