<?php
require "../../../lib/config.php";

global $pdo;

if ($_POST["acao"] == "atualizar") {
    $query = "UPDATE login SET nome = :n, email = :e, usuario = :u, tipo = :t, cargo = :cg, assinante_documento = :ad, perfil_assinaturas = :pa "
        . "WHERE codigo = :c";

    $update = $pdo->prepare($query);
    $update->bindValue(":n", $_POST["nome"]);
    $update->bindValue(":e", $_POST["email"]);
    $update->bindValue(":u", $_POST["usuario"]);
    $update->bindValue(":t", $_POST["tipo"]);
    $update->bindValue(":c", $_POST["codigo"]);
    $update->bindValue(':cg', $_POST['cargo']);
    $update->bindValue(':ad', $_POST['assinante_documento']);
    $update->bindValue(':pa', json_encode($_POST['perfil_assinaturas']));
    $update->execute();
}

if ($_POST["acao"] == "resetar") {
    $y = date("Y");

    $update = $pdo->prepare("UPDATE login SET senha = :s WHERE codigo = :c");
    $update->bindValue(":s", md5("123456"));
    $update->bindValue(":c", $_POST['codigo_usuario']);

    $update->execute();
}

if ($_POST["acao"] == "resetar-confirm") {
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 p-0">
                <h4 class="text-danger">Aviso!</h4>
                <p>Deseja resetar a senha?</p>
            </div>
            <div class="col-nd-12 p-0  d-flex justify-content-between">
                <button class="btn btn-danger btn-sm" fechar>Não</button>
                <button class="btn btn-success btn-sm" confirm acao="resetar" codigo_usuario="<?= $_POST['codigo'] ?>">
                    Sim
                </button>
            </div>
        </div>
    </div>
    <?php
}
?>
<script>
    $("button[fechar]").click(function () {
        popup_confirm.close()
    })

    $("button[confirm]").click(function () {
        let acao = $(this).attr("acao")
        let codigo_usuario = $(this).attr("codigo_usuario")
        $("button[reset]").attr("disabled", "disabled")

        $.ajax({
            url: "src/pages/actions/editUsu_action.php",
            method: "POST",
            data: {
                acao,
                codigo_usuario
            }, success: function (confirm) {
                popup_confirm.close()
            }
        })
    })
</script>