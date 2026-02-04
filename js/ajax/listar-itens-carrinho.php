    <?php
    session_start();
    require_once("../../sistema/conexao.php");

    $sessao = $_SESSION['sessao_usuario'] ?? '';

    $separar_sessao           = explode(".", $sessao);
    $pedido                   = $separar_sessao['0'];
    $num_pedido               = $separar_sessao['1'];

    $queryCar              = $pdo->query("SELECT * FROM carrinho WHERE sessao = '$sessao'");
    $resCar                = $queryCar->fetchAll(PDO::FETCH_ASSOC);
    $total_regCar          = count($resCar);
    $total_carrinho        = 0;
    $id_cliente = $resCar[0]['id_cliente'];

    $queryCli = $pdo->query("SELECT * FROM cliente WHERE id = '$id_cliente'");
    $resCli = $queryCli->fetchAll(PDO::FETCH_ASSOC);
    $nome_cliente = $resCli[0]['nome'];
    $total_pedido = 0;
echo <<<HTML
    <div class="fw-bold titulo-item">Pedido nº {$num_pedido} </div>
    <div class="fw-bold titulo-item d-flex justify-content-center">Cliente:  {$nome_cliente}</div>
HTML;
    if ($total_regCar > 0) {
        for ($i = 0; $i   < $total_regCar; $i++) {
            $id_carrinho  = $resCar[$i]['id'];
            $total_item   = $resCar[$i]['total_item'];
            $id_produto   = $resCar[$i]['id_produto'];
            $quantidade   = $resCar[$i]['quantidade'];
            $obs          = $resCar[$i]['observacoes'];
            $valor_unit   = $total_item / $quantidade;
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

            // Verificar se o produto tem ingredientes ou adicionais
            $tem_ingredientes = false;
            $tem_adicionais = false;

            // Verifica ingredientes
            $queryIngCheck = $pdo->query("SELECT id FROM ingredientes WHERE produto = '$id_produto' LIMIT 1");
            if ($queryIngCheck->fetch()) {
                $tem_ingredientes = true;
            }

            // Verifica adicionais
            $queryAdcCheck = $pdo->query("SELECT id FROM adicionais WHERE produto = '$id_produto' AND ativo = 'Sim' LIMIT 1");
            if ($queryAdcCheck->fetch()) {
                $tem_adicionais = true;
            }

            $mostrar_icone_edicao = $tem_ingredientes || $tem_adicionais;

echo <<<HTML
                <li class="list-group-item item-carrinho">
                    <div class="topo-item d-flex justify-content-between align-items-start">
                        <img src="sistema/painel/images/produtos/{$foto}" width="30px" alt="{$nome_produto}}">
                        <div class="descricao-item ms-2 me-auto text-start">
                            <strong>{$nome_produto}</strong>
HTML;
                            $queryTemp       = $pdo->query("SELECT * FROM carrinho_temp WHERE carrinho = '$id_carrinho'");
                            $resTemp         = $queryTemp->fetchAll(PDO::FETCH_ASSOC);
                            $total_regTemp   = count($resTemp);
                            for ($j = 0; $j < $total_regTemp; $j++) { 
                                $id_item    = $resTemp[$j]['id_item'];
                                $tabela     = $resTemp[$j]['tabela'];

                                if ($tabela == 'ingredientes') {
                                $queryIng           = $pdo->query("SELECT * FROM ingredientes WHERE id = '$id_item'");
                                $resIng             = $queryIng->fetchAll(PDO::FETCH_ASSOC);
                                $nome_ingrediente   = $resIng[0]['nome'];
echo <<<HTML
                            <div class="menor">Sem {$nome_ingrediente}</div>
HTML;                            
                            } elseif ($tabela == 'adicionais') {    
                                $queryAd           = $pdo->query("SELECT * FROM adicionais WHERE id = '$id_item'");
                                $resAd             = $queryAd->fetchAll(PDO::FETCH_ASSOC);
                                $nome_adicional   = $resAd[0]['nome'];
echo <<<HTML
                                <div class="menor-verde">{$nome_adicional} Adicionado</div>
HTML;
                            }
                        }
echo <<<HTML
                            </div>    
                        </div>
                        <button class="btn-icone text-end">
                            <a  href="#" onclick="excluir('{$id_carrinho}')" class="link-neutro"><i class="bi bi-trash-fill text-danger"  
                                title="Excluir item"></i></a>
                        </button>
                        
                        <div id="popup-excluir{$id_carrinho}" class="overlay-excluir">
                            <div class="popup">
                                <div class="row">
                                    <div class="col-12">
                                        Confirmar Exclusão? <a href="#" onclick="excluirItem({$id_carrinho})" class="text-danger">Sim</a>
                                    </div>
                                    <div class="col-3">
                                        <a class="close" href="#" onclick="fecharExcluir('{$id_carrinho}')">&times;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="qtd">
HTML;
                    if ($mostrar_icone_edicao):
echo <<<HTML
                    <button class="btn-icone edt-item">
                        <a class="link-neutro" href="#" onclick="editarItem('{$id_carrinho}', '{$nome_produto}')">
                            <i class="bi bi-pencil-fill txt-azul" title="Editar Item"></i>
                            <span class="txt-azul" title="Editar Item">Adic | Ingr</span>
                        </a>
                    </button>
HTML;
                     endif;
echo <<<HTML
                        <button class="btn-icone obs-item-carrinho">
                            <a class="link-neutro" href="#" onclick="obs('{$nome_produto}', '{$obs}', '{$id_carrinho}')">
                                <i class="bi bi-chat-left-dots-fill {$classe_obs}" title="{$classe_title}"></i>
                            </a>
                        </button>
HTML;
                    if ($quantidade > 1) {
echo <<<HTML
                            <button class="btn-icone">
                                <a href="#" onclick="mudarQtd('{$id_carrinho}', '{$quantidade}', 'menos')">
                                    <i class="bi bi-dash-circle-fill text-danger icone-qtd" title="Diminuir quantidade"></i>
                                </a>
                            </button>
HTML;
                    } else {
echo <<<HTML
                        <button class="btn-icone" disabled>
                            <i class="bi bi-dash-circle-fill text-secondary icone-qtd" title="Quantidade mínima atingida"></i>
                        </button>
HTML;
                    }
echo <<<HTML
                        <span class="fw-bold valor-qtd" id="qtd">{$quantidade}</span>
                        <button class="btn-icone">
                            <a  href="#" 
                                onclick="mudarQtd('{$id_carrinho}','{$quantidade}', 'mais')" >
                                <i class="bi bi-plus-circle-fill text-success icone-qtd" title="Aumentar quantidade"></i>
                            </a>
                        </button>
                        <strong class="valor-carrinho-item">{$total_itemF}</strong>
HTML;
            if (!empty($observacoes)):
echo <<<HTML
                            <div class="obs">
                                <strong></strong>{$observacoes}
                            </div>
HTML;
            endif;
echo <<<HTML
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
        echo "<script>window.location=`index`</script>";
    }

    ?>
    <script type="text/javascript">
        function mudarQtd(id, quantidade, acao) {
            $.ajax({
                url: 'js/ajax/mudar-qtd-carrinho.php',
                method: 'POST',
                data: {
                    id,
                    quantidade,
                    acao
                },
                dataType: 'text',

                success: function(mensagem) {
                    if (mensagem.trim() == 'Alterado com sucesso!') {
                        listarCarrinho();
                    }
                }
            });
        }

        function excluirItem(id) {
            $.ajax({
                url: 'js/ajax/excluir-item-carrinho.php',
                method: 'POST',
                data: {
                    id
                },
                dataType: 'text',

                success: function(mensagem) {
                    if (mensagem.trim() == 'Excluído com sucesso!') {
                        listarCarrinho();
                    }
                }
            });
        }

        function excluir(id) {
            var popup = 'popup-excluir' + id;
            document.getElementById(popup).style.display = 'block';
        }

        function fecharExcluir(id) {
            var popup = 'popup-excluir' + id;
            document.getElementById(popup).style.display = 'none';
        }

        function obs(nome, obs, id) {
            $('#obs').val("");    
            $('#nome_item').text(nome);
            $('#obs').val(obs);
            $('#id_obs').val(id);
            var modalObservacoes = new bootstrap.Modal(document.getElementById('modalobs'),{

            });
            modalObservacoes.show();
        }
        
        function editarItem(id, nome) {
            $('#nome_item_add').text(nome);
            listarAdd(id);
        }

        function listarAdd(id) {
            $.ajax({
                url: 'js/ajax/listar-add-car.php',
                method: 'POST',
                data: {id},
                dataType: 'html',
                success: function(result) {
                    $('#listar-add-car').html(result); // ← removi os espaços
                    var modalEdtItem = new bootstrap.Modal(document.getElementById('modalEdtItem'));
                    modalEdtItem.show(); // ← só abre depois de carregar
                }
            });
            modalEdtItem.show();
        };

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