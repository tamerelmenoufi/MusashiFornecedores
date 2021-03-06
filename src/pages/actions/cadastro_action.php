<?php
require '../../../lib/config.php';

if ($_POST['nome'] && $_POST['email'] && $_POST['usuario'] && $_POST['senha']) {
    global $pdo;

    $sql = $pdo->prepare("SELECT codigo FROM login WHERE email = :e");
    $sql->bindValue(':e', $_POST['email']);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-danger">Ops...</h3>
                    <p>E-mail já cadastrado.</p>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button sing_in type="button" class="btn btn-secondary btn-sm">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    } else {
        $sql = $pdo->prepare("INSERT INTO login (nome, email, usuario, senha, tipo, cargo, assinante_documento) VALUES (:n, :e, :u, :s, :t, :cg, :ad)");

        $sql->bindValue(':n', $_POST['nome']);
        $sql->bindValue(':e', $_POST['email']);
        $sql->bindValue(':u', $_POST['usuario']);
        $sql->bindValue(':s', md5($_POST['senha']));
        $sql->bindValue(':t', 2);
        $sql->bindValue(':cg', $_POST['cargo']);
        $sql->bindValue(':ad', $_POST['assinante_documento']);
        $sql->execute();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-alert">Concluido</h3>
                    <p>Cadastro efetuado com sucesso.</p>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button login type="button" class="btn btn-success btn-sm">Ok</button>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<script>
    $('button[login]').click(function () {
        popup_cadastro_usuario.close()
        location.reload()
    })

    $('button[sing_in]').click(function () {
        popup_cadastro_usuario.close()
    })
</script>