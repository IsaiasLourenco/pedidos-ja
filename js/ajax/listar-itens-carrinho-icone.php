    <?php
    session_start();
    require_once("../../sistema/conexao.php");

    $sessao = $_SESSION['sessao_usuario'] ?? '';

    $queryCar              = $pdo->query("SELECT * FROM carrinho WHERE sessao = '$sessao'");
    $resCar                = $queryCar->fetchAll(PDO::FETCH_ASSOC);
    $total_regCar          = count($resCar);
    $total_carrinho        = 0;
    $total_pedido          = 0;
    if ($total_regCar > 0) {
        for ($i = 0; $i   < $total_regCar; $i++) {
            $id_carrinho  = $resCar[$i]['id'];
            $total_item   = $resCar[$i]['total_item'];
            $id_produto   = $resCar[$i]['id_produto'];
            $quantidade   = $resCar[$i]['quantidade'];
            $quantidade_formatada = str_pad($quantidade, 2, '0', STR_PAD_LEFT); // ← aqui!
            $obs          = $resCar[$i]['observacoes'];
            $valor_unit   = $total_item / $quantidade;
            $valor_unitF  = 'R$ ' . number_format($valor_unit, 2, ',', '.');
            $total_itemF  = 'R$ ' . number_format($total_item, 2, ',', '.');
            $total_pedido += $total_item;
            $total_pedidoF = 'R$ ' . number_format($total_pedido, 2, ',', '.');
            if (empty(trim($obs))) {
                $classe_obs = "";
                $classe_title = "Item sem observação";
            } else {
                $classe_obs = "text-danger";
                $classe_title = "Observação para o item";
            }

            $queryPro = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
            $resPro = $queryPro->fetchAll(PDO::FETCH_ASSOC);
            $id_produto = $resPro[0]['id'];
            $nome_produto = $resPro[0]['nome'];
            $foto = $resPro[0]['foto'];
            $valor_produto = $resPro[0]['valor_venda'];

            if (abs($valor_unit - $valor_produto) > 0.01) {
            // É uma variação → buscar nome da variação
            $queryVar = $pdo->query("SELECT nome FROM variacoes WHERE produto = '$id_produto' AND valor = '$valor_unit' LIMIT 1");
            $resVar = $queryVar->fetch(PDO::FETCH_ASSOC);
            if ($resVar) {
                $nome_exibicao = $resVar['nome'];
            } else {
                $nome_exibicao = $nome_produto; // fallback
            }
            } else {
                // Produto sem variação
                $nome_exibicao = $nome_produto;
            }

echo <<<HTML
                <li class="list-group-item item-carrinho">
                    <div class="topo-item d-flex justify-content-between align-items-start">
                        <img src="sistema/painel/images/produtos/{$foto}" width="30px" alt="{$nome_produto}}">
                        <div class="descricao-item ms-2 me-auto text-start">
                            <strong>{$quantidade_formatada}&nbsp;&nbsp;{$nome_exibicao}</strong>&nbsp;&nbsp;<span class="valor-unitario">{$valor_unitF}</span>
                        </div>
                        <button class="btn-icone text-end">
                            <a  href="#" onclick="excluirIcone('{$id_carrinho}')" class="link-neutro"><i class="bi bi-trash-fill text-danger"  
                                title="Excluir item"></i></a>
                        </button>
                        
                        <div id="popup-excluir{$id_carrinho}" class="overlay-excluir">
                            <div class="popup">
                                <div class="row">
                                    <div class="col-12">
                                        Confirmar Exclusão? 
                                        <a href="#" onclick="excluirItemIcone({$id_carrinho}); return false;">Sim</a>
                                    </div>
                                    <div class="col-3">
                                        <a class="close" href="#" onclick="fecharExcluirIcone('{$id_carrinho}')">&times;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                 <input type="hidden" id="quantidaade" value="1">
                <input type="hidden" id="total_item_input" value="{$valor_unit}">
HTML;
        }
echo <<<HTML

            <div class="total-item">
                    <strong>Total do Pedido - {$total_pedidoF}</strong>
                </div>
HTML;
    } else {
            echo "<div class='text-center'>Carrinho vazio</div>";
            exit;
}

    ?>
    <script type="text/javascript">
        $('#total-itens-carriho').text("<?php echo $total_regCar?>")

        function excluirItemIcone(id) {
            $.ajax({
                url: 'js/ajax/excluir-item-carrinho-icone.php',
                method: 'POST',
                data: {
                    id
                },
                dataType: 'text',

                success: function(mensagem) {
                    if (mensagem.trim() == 'Excluído com sucesso!') {
                        location.reload();
                    }
                }
            });
        }

        function excluirIcone(id) {
            var popup = 'popup-excluir' + id;
            document.getElementById(popup).style.display = 'block';
        }

        function fecharExcluirIcone(id) {
            var popup = 'popup-excluir' + id;
            document.getElementById(popup).style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.overlay-excluir').forEach(modal => {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                    }
                });
            }
        });

    </script>