<?php
@session_start();
require_once('../../sistema/conexao.php');

$id_carrinho = $_POST['id'];

// Buscar o id_produto do item no carrinho
$query = $pdo->query("SELECT id_produto FROM carrinho WHERE id = '$id_carrinho'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$id_produto = $res['id_produto'];

// === 1. BUSCAR TODOS OS INGREDIENTES DO PRODUTO ===
$queryIng = $pdo->query("SELECT * FROM ingredientes WHERE produto = '$id_produto' ORDER BY nome");
$ingredientes = $queryIng->fetchAll(PDO::FETCH_ASSOC);

// === 2. BUSCAR TODOS OS ADICIONAIS DO PRODUTO (ou da categoria) ===
// Supondo que adicionais estejam vinculados à categoria do produto
$queryProd = $pdo->query("SELECT categoria FROM produtos WHERE id = '$id_produto'");
$categoria = $queryProd->fetch(PDO::FETCH_ASSOC)['categoria'];

// Buscar adicionais VINCULADOS AO PRODUTO ESPECÍFICO
$queryAdc = $pdo->query("SELECT * FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim' ORDER BY nome");
$adicionais = $queryAdc->fetchAll(PDO::FETCH_ASSOC);

// === 3. BUSCAR ITENS JÁ SELECIONADOS NO CARRINHO_TEMP ===
$queryTemp = $pdo->query("SELECT * FROM carrinho_temp WHERE carrinho = '$id_carrinho'");
$itens_selecionados = [];
while ($row = $queryTemp->fetch(PDO::FETCH_ASSOC)) {
    $itens_selecionados[$row['tabela']][$row['id_item']] = true;
}
?>

<!-- Ingredientes -->
<h6 class="mt-3">Remover ingredientes</h6>
<small class="text-muted">Desmarque para remover</small>
<?php if (count($ingredientes) > 0): ?>
    <?php foreach ($ingredientes as $ing): ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                name="ingredientes[]"
                value="<?= $ing['id'] ?>"
                id="ing_<?= $ing['id'] ?>"
                <?= isset($itens_selecionados['ingredientes'][$ing['id']]) ? '' : 'checked' ?>>
            <label class="form-check-label" for="ing_<?= $ing['id'] ?>">
                <?= htmlspecialchars($ing['nome']) ?>
            </label>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-muted">Nenhum ingrediente configurado</p>
<?php endif; ?>

<!-- Adicionais -->
<h6 class="mt-4">Adicionar extras</h6>
<small class="text-muted">Marque para incluir</small>
<?php if (count($adicionais) > 0): ?>
    <?php foreach ($adicionais as $adc): ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                name="adicionais[]"
                value="<?= $adc['id'] ?>"
                id="adc_<?= $adc['id'] ?>"
                <?= isset($itens_selecionados['adicionais'][$adc['id']]) ? 'checked' : '' ?>>
            <label class="form-check-label" for="adc_<?= $adc['id'] ?>">
                <?= htmlspecialchars($adc['nome']) ?>
                <span class="text-danger">(R$ <?= number_format($adc['valor'], 2, ',', '.') ?>)</span>
            </label>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-muted">Nenhum adicional disponível</p>
<?php endif; ?>

<!-- Campo oculto para ID do carrinho -->
<input type="hidden" id="id_carrinho_edit" value="<?= $id_carrinho ?>">

<script>
    function salvarAlteracoesItem() {
        var id_carrinho = $('#id_carrinho_edit').val();
        var ingredientes = [];
        var adicionais = [];

        // Coletar ingredientes DESMARCADOS (para remover)
        $('input[name="ingredientes[]"]:not(:checked)').each(function() {
            ingredientes.push($(this).val());
        });

        // Coletar adicionais MARCADOS (para adicionar)
        $('input[name="adicionais[]"]:checked').each(function() {
            adicionais.push($(this).val());
        });

        $.ajax({
            url: 'js/ajax/salvar-item-add.php',
            method: 'POST',
            data: { // ← AQUI ESTAVA FALTANDO "data:"
                id_carrinho: id_carrinho,
                ingredientes_remover: ingredientes,
                adicionais_adicionar: adicionais
            },
            success: function(mensagem) {
                if (mensagem.trim() === 'Salvo com sucesso!') {
                    $('#modalEdtItem .btn-close').click();
                    listarCarrinho();
                } else {
                    alert('Erro: ' + mensagem);
                }
            }
        });
    }
</script>

<!-- Botão de salvar dentro da modal (adicione no carrinho.php) -->
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-primary" onclick="salvarAlteracoesItem()">Salvar Alterações</button>
</div>