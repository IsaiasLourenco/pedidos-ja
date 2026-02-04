 <!-- Ícone do carrinho -->
<div class="topo">
    <a href="#" id="carrinho-icone" class="text-dark carrinho" aria-label="Carrinho">
        <div class="d-flex">
            <i class="bi bi-cart-fill"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pilula">
                <span id="total-itens-carriho">0</span>
                <span class="visually-hidden">Itens no carrinho</span>
            </span>
        </div>
    </a>
</div>

<!-- Popup do carrinho -->
<div id="popup-cart" class="overlay" style="visibility: hidden; opacity: 0;">
    <div class="popup">
        <div class="row">
            <div class="col-9">
                <h3 class="titulo-popup"><a href="carrinho" class="link-neutro text-primary"><i class="bi bi-cart4"></i>&nbsp;&nbsp;Ir para o carrinho</a></h3>
            </div>
            <div class="col-3 text-end">
                <button class="close" id="fechar-carrinho">&times;</button>
            </div>
        </div>
        <hr class="linha">
        <div class="conteudo-popup">
            <ol class="list-group mt-6" id="listar-itens-carrinho-icone">
                
            </ol>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const icone = document.getElementById('carrinho-icone');
        const popup = document.getElementById('popup-cart');
        const fecharBtn = document.getElementById('fechar-carrinho');

        if (!icone || !popup || !fecharBtn) {
            console.error("Elementos do carrinho não encontrados!");
            return;
        }

        // Função de listagem
        function listarCarrinhoIcone() {
            $.ajax({
                url: 'js/ajax/listar-itens-carrinho-icone.php',
                method: 'POST',
                dataType: 'html',
                success: function(mensagem) {
                    $('#listar-itens-carrinho-icone').html(mensagem);
                },
                error: function() {
                    console.error("Erro ao carregar itens do carrinho");
                }
            });
        }

        // Inicializar
        listarCarrinhoIcone();

        // Eventos
        icone.addEventListener('mouseenter', () => {
            popup.style.visibility = 'visible';
            popup.style.opacity = '1';
        });

        icone.addEventListener('mouseleave', () => {
            setTimeout(() => {
                if (!popup.matches(':hover')) {
                    esconderPopup();
                }
            }, 100);
        });

        popup.addEventListener('mouseleave', esconderPopup);
        fecharBtn.addEventListener('click', esconderPopup);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                esconderPopup();
            }
        });

        function esconderPopup() {
            popup.style.visibility = 'hidden';
            popup.style.opacity = '0';
        }
    });
</script>