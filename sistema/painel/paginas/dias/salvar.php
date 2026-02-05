<?php
require_once('../../../conexao.php');
$tabela = 'dias';
$id = $_POST['id'];
$dia = $_POST['dia'];

//Validar Nome
$query = $pdo->query("SELECT * FROM $tabela WHERE dia = '$dia'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "Dia já cadastrado!!";
    exit;
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET dia = :dia");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET dia = :dia
                                           WHERE id = '$id'");
}
$query->bindValue(":dia", "$dia");
$query->execute();
echo 'Salvo com Sucesso';
?>