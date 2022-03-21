<style>
    #home {
        margin: 0 !important;
    }
</style>
<div class="container-fluid position-absolute h-100">
    <form class="row justify-content-center align-items-center h-100  needs-validation" novalidate>
        <div class="col-md-8 card p-0">
            <div class="card-header fw-bolder">
                <h3><i class="fa fa-wpforms" aria-hidden="true"></i> Cadastro de Fornecedor</h3> 
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
                    <div class="col-md-12 mt-4">
                        <button salvar class="btn btn-success pull-right fw-bolder" type="button">Cadastrar</button>                       
                        <button voltar type="button" class="btn btn-light fs-6"><i class="fa fa-angle-left" aria-hidden="true"></i> voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    $('button[voltar]').click(function(){
        $.ajax({
            url: 'src/fornecedor/fornecedor_lista.php',
            success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })
    
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

    $('.cnpj').mask('00.000.000/0000-00')
</script>