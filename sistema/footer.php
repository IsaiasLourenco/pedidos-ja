<footer class="rodape">

    <img src="/delivery-interativo/img/<?= htmlspecialchars($logotipo) ?>" width="30" height="30" alt="Logo" class="logo">
    <?php echo $nome_sistema ?><br>
    <?php echo $endereco_sistema ?>
    <a href="https://api.whatsapp.com/send?phone=<?php echo $telefone_url ?>; ?>
            &text=Ol%C3%A1!%20Gostaria%20de%20fazer%20um%20pedido%20no%20seu%20Delivery."
        target="_blank"
        class="link-neutro"><br>
        <i class="bi bi-whatsapp text-success"></i>&nbsp;<?php echo $telefone_sistema ?>
    </a>
    <a href="<?php echo $instagram_sistema; ?>" style="color: #E1306C; text-decoration: none;" target="_blank">
        <i class="bi bi-instagram"></i>&nbsp;Instagram
    </a>
</footer>