<?php
session_start();
if (!isset($_SESSION['sessao_usuario']) || empty($_SESSION['sessao_usuario'])) {
    // Se o usuário chegou aqui sem sessão, cria uma nova
    $_SESSION['sessao_usuario'] = uniqid('sess_', true);
}

require_once("./cabecalho.php");

$sessao = $_SESSION['sessao_usuario'];

?>

<div class="main-container pagina-carrinho">
    <nav class="navbar bg-body-tertiary fixed-top sombra-nav">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="index" class="link-neutro"><i class="bi bi-arrow-left"></i></a>
                <span class="ms-2">Resumo do Pedido</span>
            </div>
            
        </div>
    </nav>

    <ol class="list-group mt-6" id="listar-itens-carrinho">
        
    </ol>

    <a href="finalizar.php" class="btn btn-primary w-100 botao-finalizar">
        Finaliza Pedido →
    </a>
</div>

<?php require_once("./rodape.php"); ?>

<script>
    $(document).ready(function() {
        listarCarrinho();

        $("#form-obs").submit(function() {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'js/ajax/editar-obs-carrinho.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(mensagem) {
                    $('#mensagem-obs').text('');
                    $('#mensagem-obs').removeClass();
                    if (mensagem.trim() == "Editado com sucesso!") {
                        $('#btn-fechar-obs').click();
                        listarCarrinho();
                    } else {
                        $('#mensagem-obs').addClass('text-danger');
                        $('#mensagem-obs').text('mensagem');
                    }
                }
            });
        });
    });

    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape" && window.location.hash === "#popup-excluir") {
            window.location.hash = "";
        }
    });

    function listarCarrinho() {
        $.ajax({
            url: 'js/ajax/listar-itens-carrinho.php',
            method: 'POST',
            data: {},
            dataType: 'html',

            success: function(result) {
                $('#listar-itens-carrinho').html(result);
            },
        });
    }

</script>

</body>

</html>

<!-- MODAL OBS -->
<div class="modal" tabindex="-1" id="modalobs">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Observações - <span id="nome_item"></span></h5>
                <button type="button" id="btn-fechar-obs" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php $base = "/delivery-interativo"; ?>
            <form id="form-obs" action="<?= $base ?>/js/ajax/editar-obs-carrinho.php" method="POST">
                <div class="modal-body">
                    <div class="obs">
                        <div class="form-group">
                            <textarea name="obs" id="obs" class="textarea-observacoes"></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" id="id_obs">
                <br>
                <div class="mensagem-obs" id="mensagem-obs"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL OBS -->

<!-- MODAL EDITAR ITEM -->
<div class="modal" tabindex="-1" id="modalEdtItem">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="nome_item_add"></span></h5>
                <button type="button" id="btn-fechar-edtitem" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="obs">
                    <div class="form-group" id="listar-add-car"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL EDITAR ITEM -->