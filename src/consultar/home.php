<?php
require '../../lib/config.php';

include("../components/menu_top_fornecedor.php");

if (isset($_POST['ano'])) {
    $Y = $_POST['ano'];
} else {
    $Y = date("Y");
}

?>
<style>
    @media print {
        div[grafico] {
            height: 300px !important;
        }

        div[rs] {
            width: 100% !important;
            margin: 0 !important;
        }

        .noprint {
            display: none !important;
        }

        canvas[can] {
            width: 100% !important;
            height: 300px !important;
        }

        div.tfonts {
            font-size: 14px;
        }
    }
</style>

<!-- <button legenda class="btn btn-warning position-fixed" style="left: 30px; top: 90px; width: 40px; color: #fff; z-index: 999; font-size: 20px; font-weight: 800">?</button> -->

<div id="home" style="margin-top: 70px">
    <div class="container p-0">
        <div id="relatorio"></div>
    </div>
</div>
<script>
    $(function () {
        $.ajax({
            url: "src/consultar/listagem_mes.php",
            type: "GET",
            success: function (retorno) {
                $("div#home").html(retorno);
            }
        });
    });
</script>
