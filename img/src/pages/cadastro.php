<div tela_login class="container-fluid position-absolute h-100">
    <div class="row justify-content-center align-items-center h-100">
        <form class="col-md-4 border needs-validation" novalidate style="border-radius: 15px; overflow: hidden;">
            <header class="row fw-bolder justify-content-center bg-danger text-white p-2">
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

            <div class="col-12 mb-3 d-flex justify-content-between">
                <button confirmar_cadastro local="src/pages/actions/cadastro_action.php" class="btn btn-success btn-sm" type="submit">Confirmar</button>
                <button cancelar_cadastro local="src/pages/login.php" class="btn btn-danger btn-sm" type="button">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>

    (function () {
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
        })
    })()

    $('button[confirmar_cadastro]').click(function(){
        let local = $(this).attr('local')
        let nome = $('input#nome').val()
        let email = $('input#email').val()
        let usuario = $('input#usuario').val()
        let senha = $('input#senha').val()

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                nome,
                email,
                usuario,
                senha
            },success: function(cadastro){
                popup = $.dialog({
                    content: cadastro,
                    title: false,
                    closeIcon: false,
                    columnClass: 'col-md-offset-6 col-md-6',
                    backgroundDismiss: true
                })
            }
        })
    });

    $('button[cancelar_cadastro]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(retorno){
                $('div#body').html(retorno)
            }
        })
    })
</script>