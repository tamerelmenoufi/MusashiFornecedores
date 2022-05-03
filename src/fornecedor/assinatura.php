<?php
require "../../lib/config.php";

global $pdo;

if ($_POST['acao'] === 'assinar') {
    $senha = md5($_POST['senha']);

    $query = $pdo->prepare("SELECT codigo, nome, cargo FROM login WHERE codigo = :c AND senha = :s");
    $query->bindValue(":s", $senha);
    $query->bindValue(":c", $_SESSION['musashi_cod_usu']);
    $query->execute();

    if ($query->rowCount() > 0) {
        $usuario = $query->fetch();

        $query2 = $pdo->prepare("SELECT codigo, assinaturas FROM avaliacao_mensal WHERE codigo = :c");
        $query2->bindValue(":c", $_SESSION['cod_mensal']);
        $query2->execute();

        $assinaturas = [];

        $avaliacao_mes = $query2->fetch();

        #@formatter:off

        $assinaturas    = json_decode($avaliacao_mes['assinaturas']) ?: [];
        $data_hora      = date('Y-m-d H:i:s');
        $chave          = md5($_SESSION['musashi_cod_usu'] . $data_hora . $usuario['nome'] . $usuario['cargo']);

        $nova_assinatura = [
            "codigo"    => $_SESSION['musashi_cod_usu'],
            "usuario"   => $usuario['nome'],
            "cargo"     => $usuario['cargo'],
            "data_hora" => $data_hora,
            "chave"     => $chave,
        ];

        array_push($assinaturas, $nova_assinatura);

        $query3 = $pdo->prepare("UPDATE avaliacao_mensal SET assinaturas = :a WHERE codigo = :c");
        $query3->bindValue(':a', json_encode($assinaturas));
        $query3->bindValue(':c', $avaliacao_mes['codigo']);

        if ($query3->execute()) {
            echo json_encode([
                "status"     => true,
                "msg"        => "Assinado com sucesso",
                "codigo"     => $avaliacao_mes['codigo'],
                "dados"      => $nova_assinatura,
                "cod_mensal" => $_SESSION['cod_mensal'],
                "tipo"       => $ConfUsu['tipo']
            ]);

            unset($_SESSION['cod_mensal']);
        } else {
            echo json_encode([
                "status" => false,
                "msg"    => "Erro ao inserir assinatura",
                "error"  => $query3->errorInfo()
            ]);
        }
        #@formatter:on
    } else {
        echo json_encode([
            "status" => false,
            "msg" => "Senha incorreta!"
        ]);
    }

    exit();
}

$_SESSION['cod_mensal'] = $_POST['cod_mensal'];

?>

<div class="container container-assinatura">
    <div class="row">
        <div class="col-md-12">
            <form class="form-assinatura needs-validation" novalidate>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input
                            type="password"
                            class="form-control"
                            id="senha"
                            required
                    >
                    <div class="form-text">
                        Confirme sua senha para validação da assinatura.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mb-2">
                    <span
                            style="display: none"
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                    ></span> Validar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            });

        $(".form-assinatura").submit(function (e) {
            e.preventDefault();

            var senha = $("#senha").val();
            var codigo_fornecedor = '<?= $_POST['codigo_fornecedor']; ?>';
            var mes = '<?= $_POST['mes']; ?>';
            var ano = '<?= $_POST['ano']; ?>';

            $.ajax({
                url: "src/fornecedor/assinatura.php",
                type: "POST",
                data: {
                    acao: 'assinar',
                    senha
                },
                dataType: "JSON",
                beforeSend: function () {
                    $('.spinner-border').show();
                },
                success: function (retorno) {
                    if (retorno.status) {

                        $.ajax({
                            url: 'src/fornecedor/relatorio_fornecedor.php',
                            method: 'POST',
                            data: {
                                codigo_fornecedor,
                                ano,
                                mes
                            }, success: function (retorno) {
                                $('div#home').html(retorno);

                            }
                        });

                        setTimeout(function () {
                            $('.spinner-border').hide();

                            $(".container-assinatura").html(`<h3 class="text-success text-center">${retorno.msg}</h3>`);
                        }, 800);

                    } else {
                        $('.spinner-border').hide();
                        $.alert(retorno.msg);
                    }
                }
            })
        });
    });
</script>
