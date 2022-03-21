<div class="container-fluid">
    <form class="row justify-content-center align-items-center g-3 m-3 needs-validation" novalidate>
        <h3><i class="fa fa-wpforms" aria-hidden="true"></i> Cadastro de Fornecedor</h3> 
        <div class="col-md-9 card p-0">
            <div class="card-header fw-bolder">
                Fornecedor
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nome_fornecedor" class="form-label">Razão Social:</label>
                        <input type="text" name="nome_fornecedor" class="form-control" id="nome_fornecedor" required>
                    </div>
                    <div class="col-md-12">
                        <label for="cnpj" class="form-label">CNPJ:</label>
                        <input type="text" name="cnpj"  class="form-control cnpj" id="cnpj" maxlenght="14" required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_inicio" class="form-label">Data de Inicio:</label>
                        <input type="date" class="form-control" name="data_inicio" id="data_inicio" required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_fim" class="form-label">Data de Conclusão:</label>
                        <input type="date" class="form-control" name="data_fim" id="data_fim" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo" class="form-label">Tipo de fornecedor:</label>
                        <select id="tipo" class="form-select" required>
                        <option value="PD" selected>Padrão</option>
                        <option value="PF">Peças Fundidas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 card p-0">
            <div class="card-header fw-bolder">
                Contatos Principals
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nome_principal" class="form-label">Nome:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>    
                            <input type="text" name="nome_principal" class="form-control" id="nome_principal" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="email_principal" class="form-label">E-mail:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-envelope" aria-hidden="true"></i></i>
                            </div>
                            <input type="email" class="form-control" name="email_principal" id="email_principal" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cpf_principal" class="form-label">CPF:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-id-card" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control cpf"  name="cpf_principal" id="cpf_principal" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="contato_principal" class="form-label">Contato:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control contato" name="contato_principal" id="contato_principal" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="setor_principal" class="form-label">Setor:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-suitcase" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control" name="setor_principal" id="setor_principal" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 card p-0">
            <div class="card-header fw-bolder">
                Contato Quality (Qualidade)
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nome_quality" class="form-label">Nome:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>    
                            <input type="text" name="nome_quality" class="form-control" id="nome_quality" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="email_quality" class="form-label">E-mail:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-envelope" aria-hidden="true"></i></i>
                            </div>
                            <input type="email" class="form-control" name="email_quality" id="email_quality" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cpf_quality" class="form-label">CPF:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-id-card" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control cpf"  name="cpf_quality" id="cpf_quality" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="contato_quality" class="form-label">Contato:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control contato" name="contato_quality" id="contato_quality" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="setor_quality" class="form-label">Setor:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-suitcase" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control" name="setor_quality" id="setor_quality" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 card p-0">
            <div class="card-header fw-bolder">
                Contato Delivery (Atendimento)
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nome_delivery" class="form-label">Nome:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>    
                            <input type="text" name="nome_delivery" class="form-control" id="nome_delivery" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="email_delivery" class="form-label">E-mail:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-envelope" aria-hidden="true"></i></i>
                            </div>
                            <input type="email" class="form-control" name="email_delivery" id="email_delivery" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cpf_delivery" class="form-label">CPF:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-id-card" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control cpf"  name="cpf_delivery" id="cpf_delivery" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="contato_delivery" class="form-label">Contato:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control contato" name="contato_delivery" id="contato_delivery" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="setor_delivery" class="form-label">Setor:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-suitcase" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control" name="setor_delivery" id="setor_delivery" required>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-9 p-0">
            <button salvar class="btn btn-success w-100 fw-bolder" type="button">Cadastrar</button>
        </div>
    </form>
</div>

<script>
    popup_cadastro_resultado = ''; 

    (function () { 'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function (form) {
            $('button[salvar]').click(function(){
                // dados do fornecedor
                let empresa = $('input#nome_fornecedor').val()
                let cnpj = $('input#cnpj').val()
                let data_inicio = $('input#data_inicio').val()
                let data_fim = $('input#data_fim').val()
                let tipo = $('select#tipo').val()

                // contato principal
                let nome_principal = $('input#nome_principal').val()
                let email_principal = $('input#email_principal').val()
                let cpf_principal = $('input#cpf_principal').val()
                let contato_principal = $('input#contato_principal').val()
                let setor_principal = $('input#setor_principal').val()

                // contato quality
                let nome_quality = $('input#nome_quality').val()
                let email_quality = $('input#email_quality').val()
                let cpf_quality = $('input#cpf_quality').val()
                let contato_quality = $('input#contato_quality').val()
                let setor_quality = $('input#setor_quality').val()

                // contato delivery
                let nome_delivery = $('input#nome_delivery').val()
                let email_delivery = $('input#email_delivery').val()
                let cpf_delivery = $('input#cpf_delivery').val()
                let contato_delivery = $('input#contato_delivery').val()
                let setor_delivery = $('input#setor_delivery').val()
                
                if (!form.checkValidity()) {

                    $.alert('<h4 class="text-warning">Aviso <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h4><p>Preencha corretamente todos os campos.</p>')

                }else{

                    $.ajax({
                        url: 'src/fornecedor/actions/form_action.php',
                        method: 'POST',
                        data: {
                            empresa,
                            cnpj,
                            data_inicio,
                            data_fim,
                            tipo,
                            nome_principal,
                            email_principal,
                            cpf_principal,
                            contato_principal,
                            setor_principal,
                            nome_quality,
                            email_quality,
                            cpf_quality,
                            contato_quality,
                            setor_quality,
                            nome_delivery,
                            email_delivery,
                            cpf_delivery,
                            contato_delivery,
                            setor_delivery,
                            acao: 'salvar'
                        },success: function(retorno){
                            popup_cadastro_resultado = $.dialog({
                                content: retorno,
                                title: false,
                                closeIcon: false,
                                backgroundDismiss: true,
                                columnClass: 'col-md-offset-6 col-md-6',
                            })
                        }
                    })

                }
                form.classList.add('was-validated')
            })
        }, false)
    })()

    $('.cpf').mask('000.000.000-00')
    $('.cnpj').mask('00.000.000/0000-00')
    $('.contato').mask('(00) 0 0000-0000')
</script>