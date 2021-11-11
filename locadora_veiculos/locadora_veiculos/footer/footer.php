<?php
date_default_timezone_set('America/Sao_Paulo');
$data = new DateTime() ?>
</div>
<footer class="footer py-3 bg-midnightblue " style="bottom: 0; width: 100%; margin-top: 10px; height: auto;">
    <div class="row text-center">
        <div class="container">
            <span class="text-light text-center" style="">Data: <?= $data->format('d/m/Y H:i:s') ?></span>
            <a href="https://api.whatsapp.com/send?phone=5544988561075"
               target="_blank"><i class="fab fa-whatsapp font-size-35 float-end"></i></a>
            <a href="https://www.linkedin.com/in/lohan-matheus-cambiriba-286990222/"
               target="_blank"><i class="fab fa-linkedin font-size-35 float-end"></i></a>
        </div>

    </div>
</footer>
<script src="../script/bootstrapjs/bootstrap.bundle.js"></script>
<script src="../script/jquery/jquery-1.8.2.js"></script>
<script src="../script/jquery/jquery-ui.js"></script>
<script src="../script/bootstrapjs/bootstrap.js"></script>
<script src="../script/utils.js"></script>
<script src="../script/all.js"></script>
<script src="../script/jquery/jquery.mask.js"></script>



