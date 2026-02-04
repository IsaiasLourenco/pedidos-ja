<?php require_once("./sistema/conexao.php")?>

<footer class="rodape">
    <?php echo $endereco_sistema?>
    <a href="https://api.whatsapp.com/send?phone=<?php echo $telefone_url?>; ?>
            &text=Ol%C3%A1!%20Gostaria%20de%20fazer%20um%20pedido%20no%20seu%20Delivery."
        target="_blank"
        class="link-neutro"><br>
        <i class="bi bi-whatsapp text-success"></i>&nbsp;<?php echo $telefone_sistema?>
    </a>
    <a href="<?php echo $instagram_sistema; ?>" style="color: #E1306C; text-decoration: none;" target="_blank">
        <i class="bi bi-instagram"></i>&nbsp;Instagram
    </a>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Mask -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<!-- Seus scripts -->
<script src="./js/mascaras.js"></script>
<script src="./js/buscaCepModal.js"></script>