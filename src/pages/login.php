<?php
$home = true;
require "../../lib/config.php";
session_destroy();
?>

<div tela_login class="container-fluid position-absolute h-100">
    <div class="row justify-content-center align-items-center h-100">
        <form class="col-md-4 border px-4" style="border-radius: 15px; overflow: hidden;">
            <!-- <header class="row fw-bolder justify-content-center bg-danger text-white p-2">
                ACESSO AO PAINEL DE CONTROLE
            </header> -->
            <div class=" d-flex justify-content-center p-3">
                <img src="img/musashi.png" style="object-fit: contain;">
            </div>
            <div class="mb-3 user">
                <label for="user" class="form-label">Nome de usuário:</label>
                <input type="text" class="form-control" id="user" placeholder="">
            </div>
            <div class="mb-3 cnpj-container" style="display: none">
                <label for="user" class="form-label">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" placeholder="">
            </div>
            <div class="mb-4">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha">
            </div>

            <div class="mb-4 text-center">
                <div class="form-check form-check-inline">
                    <input
                            class="form-check-tipo"
                            type="radio"
                            name="tipo"
                            id="radio-admimistrador"
                            value="administrador"
                            checked
                    >
                    <label class="form-check-label" for="radio-admimistrador">Administrador</label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                            class="form-check-tipo"
                            type="radio"
                            name="tipo"
                            id="radio-fornecedor"
                            value="fornecedor"
                    >
                    <label class="form-check-label" for="radio-fornecedor">Fornecedor</label>
                </div>
            </div>

            <button
                    type="button" entrar local="src/pages/actions/login_action.php"
                    class="btn btn-success btn-sm mb-3 w-100 py-2">
                Entrar
            </button>
        </form>
    </div>
</div>

<script>
    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});

    $('button[entrar]').click(function () {
        let local = $(this).attr('local')
        let usuario = $('input#user').val()
        let senha = $('input#senha').val()
        let cnpj = $('input#cnpj').val()
        let tipo = $('input[name=tipo]:checked').val().trim();

        $.ajax({
            url: local,
            method: 'POST',
            dataType: 'JSON',
            data: {
                usuario,
                senha,
                cnpj,
                tipo,
                acao: 'logar'
            }, success: function (retorno) {
                let msg = `<h3 class="text-danger">Aviso!</h3><p>${retorno.msg}</p>`

                if (retorno.status === false) {
                    $.alert(msg)
                } else {

                    $.ajax({
                        url: retorno.url,
                        success: function (home) {
                            $('div#body').html(home)
                        }
                    })
                }
            }
        })
    });

    $('a[novo_usuario]').click(function () {
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function (retorno) {
                $('div#body').html(retorno);
            }
        })
    });

    $(".form-check-tipo").change(function () {
        $("input#cnpj, input#user, input#senha").val("");

        var val = $(this).val();

        if (val == "administrador") {
            $(".user").show();
            $(".cnpj-container").hide();
        } else if (val == "fornecedor") {
            $(".cnpj-container").show();
            $(".user").hide();
        }
    });
</script>