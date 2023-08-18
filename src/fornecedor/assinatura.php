<?php
require "../../lib/config.php";

global $pdo;

if ($_POST['acao'] === 'assinar') {
    #@formatter:off
    $senha          = md5($_POST['senha']);
    $tipo_relatorio = $_POST['tipo_relatorio'];

    $query = $pdo->prepare("SELECT codigo, nome, cargo FROM login WHERE codigo = :c AND senha = :s");
    $query->bindValue(":s", $senha);
    $query->bindValue(":c", $_SESSION['musashi_cod_usu']);
    $query->execute();

    if ($query->rowCount() > 0) {
        $campo_assinatura = "";

        if ($tipo_relatorio === 'IPF')     $campo_assinatura = 'assinaturas_ipf';
        elseif ($tipo_relatorio === 'IQF') $campo_assinatura = 'assinaturas_iqf';
        elseif ($tipo_relatorio === 'IAF') $campo_assinatura = 'assinaturas_iaf';

        $usuario = $query->fetch();

        $query2 = $pdo->prepare("SELECT codigo, {$campo_assinatura} FROM avaliacao_mensal WHERE codigo = :c");

        $query2->bindValue(":c", $_SESSION['cod_mensal']);
        $query2->execute();

        $assinaturas = [];

        $avaliacao_mes = $query2->fetch();

        $assinaturas    = json_decode($avaliacao_mes[$campo_assinatura]) ?: [];
        $data_hora      = date('Y-m-d H:i:s');
        $chave          = md5(
                $_SESSION['musashi_cod_usu']
                    . $data_hora
                    . $usuario['nome']
                    . $usuario['cargo']
                    . $tipo_relatorio
        );

        $nova_assinatura = [
            "codigo"    => $_SESSION['musashi_cod_usu'],
            "usuario"   => $usuario['nome'],
            "cargo"     => $usuario['cargo'],
            "data_hora" => $data_hora,
            "chave"     => $chave,
        ];

        array_push($assinaturas, $nova_assinatura);

        $query3 = $pdo->prepare("UPDATE avaliacao_mensal SET {$campo_assinatura} = :a WHERE codigo = :c");
        $query3->bindValue(':a', json_encode($assinaturas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $query3->bindValue(':c', $avaliacao_mes['codigo']);

        if ($query3->execute()) {

            $TipoDoc = [
                'assinaturas_ipf' => 'doc_ipf',
                'assinaturas_iqf' => 'doc_iqf',
                'assinaturas_iaf' => 'doc_iaf',
            ];

            $query4 = $pdo->prepare("UPDATE assinaturas SET 
                                                status = '1'
                                            WHERE 
                                                doc = '{$TipoDoc[$campo_assinatura]}' and 
                                                usuario = '{$_SESSION['musashi_cod_usu']}' and 
                                                codigo_avaliacao_mensal = '{$avaliacao_mes['codigo']}'
                                    ");
            $query4->execute();

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
            //@formatter:off
            let senha             = $("#senha").val();
            let codigo_fornecedor = '<?= $_POST['codigo_fornecedor']; ?>';
            let mes               = '<?= $_POST['mes']; ?>';
            let ano               = '<?= $_POST['ano']; ?>';
            let tipo_relatorio    = '<?= $_POST['tipo_relatorio'];?>';
            //@formatter:on

            $.ajax({
                url: "src/fornecedor/assinatura.php",
                type: "POST",
                data: {
                    acao: 'assinar',
                    senha,
                    tipo_relatorio,
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
                                mes,
                                tipo_relatorio,
                            }, success: function (retorno) {
                                $('div#home').html(retorno);
                            }
                        });

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
