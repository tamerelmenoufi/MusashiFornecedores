<?php
    require "../../../lib/config.php";

    global $pdo;


    if(isset($_POST["confirm"]) && $_POST["confirm"]  == "confirm-desativar"){
        $sql = $pdo->prepare("UPDATE login SET situacao = '0' WHERE codigo = {$_POST['codigo_usuario']}");
        $sql->execute();
    }

    if(isset($_POST["confirm"]) && $_POST["confirm"] == "confirm-ativar"){
        $sql = $pdo->prepare("UPDATE login SET situacao = 1 WHERE codigo = {$_POST['codigo_usuario']}");
        $sql->execute();
    }

    if(isset($_POST['codigo'])){
        $usu = $pdo->prepare("SELECT * FROM login WHERE codigo = {$_POST['codigo']}");
        $usu->execute();

        $usuinfo = $usu->fetch();
    }

    if($_POST["tipo"] == "desativar"){
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <h4 class="text-danger">Aviso!</h4>
                    <p>Deseja desativar o <strong class="text-danger"><?=$usuinfo['usuario']?></strong>?</p>
                </div>
                <div class="col-nd-12 p-0 d-flex justify-content-between">
                    <button class="btn btn-danger btn-sm" fechar >Não</button>
                    <button class="btn btn-success  btn-sm" confirm="confirm-desativar" codigo_usuario="<?=$_POST['codigo']?>" local="src/pages/usuarios.php">Sim</button>
                </div>
            </div>
        </div>
    <?php   
    }

    if($_POST["tipo"] == "ativar"){
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <h4 class="text-success">Aviso!</h4>
                    <p>Deseja reativar o <strong class="text-success"><?=$usuinfo['usuario']?></strong>?</p>
                </div>
                <div class="col-nd-12 p-0  d-flex justify-content-between">
                    <button class="btn btn-danger btn-sm" fechar >Não</button>
                    <button class="btn btn-success btn-sm" confirm="confirm-ativar" codigo_usuario="<?=$_POST['codigo']?>" local="src/pages/usuarios.php">Sim</button>
                </div>
            </div>
        </div>
    <?php
    }
?>
<script>
    $("button[confirm]").click(function(){
        let local = $(this).attr("local")
        let confirm = $(this).attr("confirm")
        let codigo_usuario = $(this).attr("codigo_usuario")


        $.ajax({
            url: "src/pages/actions/usuarios_action.php",
            method: "POST",
            data: {
                confirm,
                codigo_usuario
            },success: function(notUse){
                $.ajax({
                    url: local,
                    success: function(retorno){
                        popup.close()
                        $("div#home").html(retorno)
                    }
                })
            }
        })
    })

    $("button[fechar]").click(function(){
        popup.close()
    })
</script>