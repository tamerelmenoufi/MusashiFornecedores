<?php
require "../../lib/config.php";

global $pdo;

if ($_POST['acao'] === 'assinar') {
    #@formatter:off
    $senha = md5($_POST['senha']);
    $ano   = $_POST['ano'];
    $mes   = $_POST['mes'];

    $query = $pdo->prepare("SELECT codigo, nome, cargo FROM login WHERE codigo = :c AND senha = :s");
    $query->bindValue(":s", $senha);
    $query->bindValue(":c", $_SESSION['musashi_cod_usu']);
    $query->execute();

    if ($query->rowCount() > 0) {
        $usuario = $query->fetch();

        $query_select = $pdo->prepare("SELECT codigo, assinaturas FROM assinatura_geral WHERE mes = :m AND ano = :a LIMIT 1");
        $query_select->bindValue(":a", $ano);
        $query_select->bindValue(":m", $mes);
        $query_select->execute();

        $assinaturas_fetch = $query_select->fetch();

        $assinaturas = [];
        $data_hora      = date('Y-m-d H:i:s');
        $chave          = md5(
            $_SESSION['musashi_cod_usu']
            . $data_hora
            . $usuario['nome']
            . $usuario['cargo']
        );

        $nova_assinatura = [
            "codigo"    => $_SESSION['musashi_cod_usu'],
            "usuario"   => $usuario['nome'],
            "cargo"     => $usuario['cargo'],
            "data_hora" => $data_hora,
            "chave"     => $chave,
        ];

        $retorno = false;
        $error_query = '';

        if($query_select->rowCount()){
            $assinaturas = json_decode($assinaturas_fetch['assinaturas']) ?: [];
            array_push($assinaturas, $nova_assinatura);

            $query_update = $pdo->prepare("UPDATE assinatura_geral SET assinaturas = :assinaturas WHERE codigo = :codigo");
            $query_update->bindValue(':assinaturas', json_encode($assinaturas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            $query_update->bindValue(':codigo', $assinaturas_fetch['codigo']);

            $retorno = $query_update->execute();
            $error_query = $query_update->errorInfo();
        }else{
            $query_insert = $pdo->prepare('INSERT INTO assinatura_geral SET mes = :mes, ano = :ano, assinaturas = :assinaturas');
            $query_insert->bindValue(':mes', $mes);
            $query_insert->bindValue(':ano', $ano);
            $query_insert->bindValue(':assinaturas', json_encode([$nova_assinatura], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            $retorno = $query_insert->execute();
            $error_query = $query_insert->errorInfo();
        }

        if ($retorno) {
            echo json_encode([
                "status"     => true,
                "msg"        => "Assinado com sucesso",
            ]);

        } else {
            echo json_encode([
                "status"    => false,
                "msg"       => "Erro ao inserir assinatura",
                "error_sql" => $error_query,
            ]);
        }

    } else {
        echo json_encode([
            "status" => false,
            "msg"    => "Senha incorreta!"
        ]);
    }
    #@formatter:on
    exit();
}

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
            //@formatter:off
            let senha             = $("#senha").val();
            let mes               = '<?= $_POST['mes']; ?>';
            let ano               = '<?= $_POST['ano']; ?>';
            //@formatter:on

            $.ajax({
                url: "src/fornecedor/assinatura_geral.php",
                type: "POST",
                data: {
                    acao: 'assinar',
                    senha,
                    mes,
                    ano,
                },
                dataType: "JSON",
                beforeSend: function () {
                    $('.spinner-border').show();
                },
                success: function (retorno) {

                    if (retorno.status) {

                        $.ajax({
                            url: "src/pages/resumo.php",
                            method: "POST",
                            data: {
                                ano,
                                mes
                            },
                            success: function (retorno) {
                                $('div#home').html(retorno)
                            }
                        })

                        setTimeout(function () {
                            $('.spinner-border').hide();
                            $(".container-assinatura")
                                .html(`<h3 class="text-success text-center">${retorno.msg}</h3>`);
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
