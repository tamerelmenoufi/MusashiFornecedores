<?php
    require "../../lib/config.php";
    if(!isset($_SESSION['musashi_cod_usu'])){
        $retorno = "src/pages/login.php";
    }else{
        $retorno = "src/pages/home.php";
        //$retorno = "src/fornecedor/fornecedor_lista.php";
    }

    $Y = date("Y");
?>

<script>
    $(function(){
        $.ajax({
            url: "<?=$retorno?>",
            success: function(retorno){
                $('div#body').html(retorno);
            }
        })
    })
</script>