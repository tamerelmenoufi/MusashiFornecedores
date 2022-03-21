<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if($_POST['acao'] == "alterar"){
        $atual_senha = md5($_POST['atual']);

        $sql = $pdo->prepare("SELECT * FROM login WHERE codigo = '{$_POST['codigo']}' AND senha = '{$atual_senha}'");
        $sql->execute();

        if($sql->rowCount() > 0){
            $nova_senha = md5($_POST['nova']);

            try {
                $update = $pdo->prepare("UPDATE login SET senha = '{$nova_senha}' WHERE codigo = '{$_POST['codigo']}'");
                $update->execute();
            } catch (PDOException $e) {
                echo "ERRO:".$e->getMessage();
                exit;
            }

            ?>
                <div class="container-fluid text-center">
                    <p class="fs-5 fw-bolder">Senha Alterada com Sucesso!</p>
                    <button ok class="btn btn-success">Ok</button>
                </div>
            <?php
        }else{
            ?>
                <div class="container-fluid text-center">
                    <p class="fs-5 fw-bolder">Senha Atual incorreta!</p>
                    <button tentar_dnv class="btn btn-secondary">Tentar Denovo</button>
                </div>
            <?php
        }
    }
?>
<script>
    $("button[ok]").click(function(){
        popup.close()
        alerta.close()
    })

    $("button[tentar_dnv]").click(function(){
        alerta.close()
    })
</script>
