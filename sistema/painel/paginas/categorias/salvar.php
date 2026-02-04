<?php
require_once('../../../conexao.php');
require_once('../../../../funcoes.php');
$tabela = 'categorias';
$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$cor = $_POST['cor'];
$ativo = $_POST['ativo'];
$acentos = ['á','à','ã','â','ä','é','è','ê','ë','í','ì','î','ï',
            'ó','ò','õ','ô','ö','ú','ù','û','ü','ç','ñ',
            'Á','À','Ã','Â','Ä','É','È','Ê','Ë','Í','Ì','Î','Ï',
            'Ó','Ò','Õ','Ô','Ö','Ú','Ù','Û','Ü','Ç','Ñ'];
$sem_acentos = ['a','a','a','a','a','e','e','e','e','i','i','i','i',
                'o','o','o','o','o','u','u','u','u','c','n',
                'A','A','A','A','A','E','E','E','E','I','I','I','I',
                'O','O','O','O','O','U','U','U','U','C','N'];
$nome_limpo = trim($nome);
$nome_sem_acentos = str_replace($acentos, $sem_acentos, $nome_limpo);
$nome_novo = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nome_sem_acentos));
$url = preg_replace('/^-+|-+$/', '', preg_replace('/-+/', '-', $nome_novo)); // remove hífens extras

//Validar Nome
$foto = 'sem-foto.jpg';
$query = $pdo->query("SELECT * FROM $tabela WHERE nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "Categoria já cadastrada!!";
    exit;
}

//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
    $foto = $res[0]['foto'];
} else {
    $foto = 'sem-foto.jpg';
}

//VALIDAR URL
$query = $pdo->query("SELECT * FROM $tabela WHERE url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0 AND $id != $res[0]['id']) {
    echo "URL da categoria já existe!";
    exit;
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/categorias/' . $nome_img;

$imagem_temp = $_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
    $ext = pathinfo($nome_img, PATHINFO_EXTENSION);
    if ($ext == 'png' or $ext == 'jpg' or $ext == 'JPG' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'jfif') {

        //EXCLUO A FOTO ANTERIOR
        if ($foto != "sem-foto.jpg") {
            @unlink('../../images/categorias/' . $foto);
        }

        $foto = $nome_img;

        move_uploaded_file($imagem_temp, $caminho);
    } else {
        echo 'Extensão de Imagem não permitida!';
        exit();
    }
}

if ($id == "" || $id == null) {
    // INSERT (novo registro)
    $query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome,
                                                    descricao = :descricao,
                                                    foto = :foto,
                                                    cor = :cor,
                                                    ativo = :ativo,
                                                    url = :url");
} else {
    $query = $pdo->prepare("UPDATE $tabela SET  nome = :nome,
                                                descricao = :descricao,
                                                foto = :foto,
                                                cor = :cor,
                                                ativo = :ativo,
                                                url = :url
                                                WHERE id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":foto", "$foto");
$query->bindValue(":cor", "$cor");
$query->bindValue(":ativo", "$ativo");
$query->bindValue(":url", "$url");
$query->execute();
echo 'Salvo com Sucesso';
?>