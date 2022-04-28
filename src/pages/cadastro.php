<style>
    #home {
        margin: 0 !important;
    }
</style>

<div tela_login class="container-fluid h-100">
    <div class="row justify-content-center align-items-center h-100">
        <form class="col-md-4 border needs-validation mb-3" novalidate style="border-radius: 15px; margin-top: 6rem">
            <header class="row fw-bolder justify-content-center bg-danger text-white p-2 mb-1">
                CADASTRO DE USUARIO
            </header>
            <div class="d-flex justify-content-center p-1">
                <img src="img/musashi.png" style="object-fit: contain;">
            </div>
            <div class="col-md-12 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" value="" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" value="" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usuario" value="" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="text" class="form-control" id="senha" value="" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="cargo" class="form-label">Cargo:</label>
                <input cargo id="cargo" type="text" class="form-control">
            </div>

            <div class="col-md-12 mb-3 form-switch">
                <input
                        class="form-check-input"
                        type="checkbox"
                        id="assinante_documento"
                >
                <label class="form-check-label" for="assinante_documento">Assinante de documento?</label>
            </div>

            <div class="col-12 mb-3 d-flex justify-content-between">
                <button cancelar_cadastro local="src/pages/login.php" class="btn btn-secondary" type="button">Cancelar
                </button>
                <button confirmar_cadastro local="src/pages/actions/cadastro_action.php" class="btn btn-success"
                        type="button">Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<script>

    popup_cadastro_usuario = '';

    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function (form) {
            $('button[confirmar_cadastro]').click(function () {
                let local = $(this).attr('local')
                let nome = $('input#nome').val()
                let email = $('input#email').val()
                let usuario = $('input#usuario').val()
                let senha = $('input#senha').val()
                let cargo = $('input#cargo').val()
                let assinante_documento = $("#assinante_documento").is(':checked') ? 'S' : 'N'

                if (!form.checkValidity()) {
                    let content = `<div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <h3 class="text-danger">Ops...</h3>
                                            <p>Preencha corretamente todos os campos!</p>
                                        </div>
                                    </div>
                                </div>`
                    $.alert(content)
                } else {
                    $.ajax({
                        url: local,
                        method: 'POST',
                        data: {
                            nome,
                            email,
                            usuario,
                            senha,
                            cargo,
                            assinante_documento
                        }, success: function (cadastro) {
                            popup_cadastro_usuario = $.dialog({
                                content: cadastro,
                                title: false,
                                closeIcon: false,
                                columnClass: 'col-md-offset-2 col-md-10',
                                backgroundDismiss: true
                            })
                        }
                    })
                }
                form.classList.add('was-validated')
            });
        }, false)
    })()

    $('button[cancelar_cadastro]').click(function () {
        location.reload()
    })


    $('.cpf').mask('000.000.000-00')
    $('.contato').mask('(00) 0 0000-0000')
</script>