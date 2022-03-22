<?php
    require "../../lib/config.php";

    global $pdo;

    if($_POST['tipo'] == "editar"){
        $sql = $pdo->prepare("SELECT * FROM login  WHERE codigo = :c");
        $sql->bindValue(":c", $_POST["codigo"]);
        $sql->execute();

        $user = $sql->fetch();
    ?>
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12 p-0">
                    <h4>Dados  do Usuário</h4>
                </div>
                <div class="col-md-12 p-0">
                    <label for="nome" class="fs-6 fw-bolder">Nome:</label>
                    <div class="input-group">
                        <div class="input-group-text" style="width: 40px">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <input nome iid="nome" type="text" class="form-control" value="<?=utf8_encode($user["nome"])?>">
                    </div>
                </div>
                <div class="col-md-12 p-0">
                    <label for="email" class="fs-6 fw-bolder">E-mail:</label>
                    <div class="input-group">
                        <div class="input-group-text" style="width: 40px">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <input email id="email" type="text" class="form-control" value="<?=utf8_encode($user["email"])?>">
                    </div>
                </div>

                <div class="col-md-12 p-0">
                    <label for="email" class="fs-6 fw-bolder">Perfil:</label>
                    <div class="input-group">
                        <div class="input-group-text" style="width: 40px">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                        <select tipo class="form-control" name="tipo" id="tipo">
                            <option value="2" <?=(($user["tipo"] == '2')?'selected':false)?>>Usuário</option>
                            <option value="1" <?=(($user["tipo"] == '1')?'selected':false)?>>Gestor</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-8" style="padding: 0 12px 0 0">
                    <label for="usuario" class="fs-6 fw-bolder">Usuário:</label>
                    <div class="input-group">
                        <div class="input-group-text" style="width: 40px">
                            <i class="fa fa-key" aria-hidden="true"></i>
                        </div>
                        <input usuario id="usuario" type="text" class="form-control" value="<?=utf8_encode($user["usuario"])?>">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end p-0">
                    <button reset class="btn btn-outline-success" codigo_usuario="<?=$_POST['codigo']?>">Resetar Senha</button>
                </div>
                <div class="col-nd-12 p-0 d-flex justify-content-between">
                    <button class="btn btn-danger" fechar>Fechar</button>
                    <button class="btn btn-success" confirm codigo_usuario="<?=$_POST['codigo']?>" local="src/pages/actions/editUsu_action.php">Confirmar</button>
                </div>
            </div>
        </div>
    <?php
    }
?>
<script>
    $("button[fechar]").click(function(){
        popup.close()
    })

    $("button[reset]").click(function(){
        let codigo = $(this).attr("codigo_usuario")

        $.ajax({
            url: "src/pages/actions/editUsu_action.php",
            method: "POST",
            data: {
                codigo,
                acao: "resetar-confirm"
            },success: function(confirm){
                popup_confirm = $.dialog({
                    content: confirm,
                    closeIcon: false,
                    title: false,
                    columnClass: "col-md-offset-9 col-md-3"
                })
            }
        })
    })

    $("button[confirm]").click(function(){
        let local = $(this).attr("local")
        let codigo = $(this).attr("codigo_usuario")
        let nome = $("input[nome]").val()
        let email = $("input[email]").val()
        let usuario = $("input[usuario]").val()
        let tipo = $("select[tipo]").val()


        $.ajax({
            url: local,
            method: "POST",
            data: {
                codigo,
                nome,
                email,
                usuario,
                tipo,
                acao: "atualizar"
            },success: function(retorno){
                popup.close();
                $.ajax({
                    url: "src/pages/usuarios.php",
                    success: function(refresh){
                        $("div#home").html(refresh)
                    }
                })
            }
        })
    })
</script>