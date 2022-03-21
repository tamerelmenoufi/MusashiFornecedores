<?php
    require "../../lib/config.php";

    global $pdo;

    $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
    $sql->bindValue(':c', $_POST['codigo_fornecedor']);
    $sql->execute();

    $d = $sql->fetch();
?>

<div class="container-fluid">
    <form class="row justify-content-center align-items-center g-3 m-3 needs-validation" novalidate>
        <div class="col-md-9 p-0 d-flex align-items-center justify-content-between">
            <h3><i class="fa fa-wpforms" aria-hidden="true"></i> Editar de Fornecedor</h3>

            <button voltar type="button" class="btn btn-light fs-6"><i class="fa fa-angle-left" aria-hidden="true"></i> voltar</button>
        </div>
        <div class="col-md-9 card p-0">
            <div class="card-header fw-bolder">
                Fornecedor
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nome_fornecedor" class="form-label">Razão Social:</label>
                        <input type="text" name="nome_fornecedor" class="form-control" id="nome_fornecedor" value="<?=utf8_encode($d['nome'])?>" required>
                    </div>
                    <div class="col-md-12">
                        <label for="cnpj" class="form-label">CNPJ:</label>
                        <input type="text" name="cnpj"  class="form-control cnpj" id="cnpj" maxlenght="14" value="<?=$d['cnpj']?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_inicio" class="form-label">Data de Inicio:</label>
                        <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="<?=$d['data_inicio']?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_fim" class="form-label">Data de Conclusão:</label>
                        <input type="date" class="form-control" name="data_fim" id="data_fim" value="<?=$d['data_fim']?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo" class="form-label">Tipo de fornecedor:</label>
                        <select id="tipo" class="form-select" required>
                        <?php
                            if($d['tipo'] == 'PD'){
                                $pdSelect = 'selected';
                                $pfSelect = '';
                            }else{
                                $pfSelect = 'selected';
                                $pdSelect = '';
                            }
                        ?>
                        <option <?=$pdSelect?> value="PD">Padrão</option>
                        <option <?=$pfSelect?> value="PF">Peças Fundidas</option>
                        </select>
                    </div>
                    <div class="col-md-12 text-end">
                        <button atualizar cod="<?=$d['codigo']?>" class="btn btn-success fw-bolder" type="button">Atualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row justify-content-center align-items-center g-3 m-3">
            <div class="col-md-9 d-flex align-items-center justify-content-between">
                <h3><i class="fa fa-phone" aria-hidden="true"></i> Contatos:</h3>
            </div>
            <div contatos class="col-md-9">
                <?php
                    $sql = $pdo->prepare("SELECT * FROM contatos WHERE codigo_fornecedora = :c");
                    $sql->bindValue(':c', $_POST['codigo_fornecedor']);
                    $sql->execute();

                    if($sql->rowCount() > 0){

                        while($d = $sql->fetch()){ 
                    ?>
                        <div class="row border p-3" style="border-left: 5px solid green !important; margin: 0px 0px 12px 0px">
                            <div class="col-md-4 fw-bolder fs-6">
                                <span class="text-secondary" style="font-size: 11px">Nome:</span><br>
                                <p><?=utf8_encode($d["nome"])?></p>
                            </div>
                            <div class="col-md-4 fw-bolder fs-6">
                                <span class="text-secondary" style="font-size: 11px">E-mail:</span><br>
                                <p><?=$d["email"]?></p>
                            </div>
                            <div class="col-md-2 fw-bolder fs-6">
                                <span class="text-secondary" style="font-size: 11px">Telefone/WhatsApp:</span><br>
                                <p><?=$d["contato"]?></p>
                            </div>
                            <div class="col-md-2 fw-bolder fs-6">
                                <span class="text-secondary" style="font-size: 11px">Setor/Função:</span><br>
                                <p><?=utf8_encode($d["setor"])?></p>
                            </div>
                        </div>
                    <?php
                        }

                    }else{
                    ?>
                        <div class="col-md-12 fw-bolder text-center border text-secondary fs-6 p-5">
                            Nenhum contato registrado.
                        </div>
                    <?php
                    }
                ?>
            </div>
            <div class="col-md-9">
                <button add_contato cod="<?=$_POST['codigo_fornecedor']?>" class="btn btn-success btn-sm fw-bolder pull-right" type="button">Adicionar Contato +</button>
            </div>
        </div>
</div>

<script>
    $("button[add_contato]").click(function(){
        let codigo_fornecedor = $(this).attr("cod")

        $.ajax({
            url: "src/fornecedor/contato.php",
            method: "POST",
            data: { codigo_fornecedor },
            success: function(retorno){
                popup_add_contato = $.dialog({
                    content: retorno,
                    title: false,
                    closeIcon: false,
                    backgroundDismiss: true,
                    columnClass: 'col-md-offset-6 col-md-6',

                })
            }
        })
    })

    $('button[voltar]').click(function(){
        $.ajax({
            url: 'src/fornecedor/fornecedor_lista.php',
            success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })

    popup_att_resultado = ''; 


    (function () { 'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function (form) {
            $('button[atualizar]').click(function(){
                // dados do fornecedor
                let codigo = $(this).attr("cod")
                let empresa = $('input#nome_fornecedor').val()
                let cnpj = $('input#cnpj').val()
                let data_inicio = $('input#data_inicio').val()
                let data_fim = $('input#data_fim').val()
                let tipo = $('select#tipo').val()

                
                if (!form.checkValidity()) {

                    $.alert('<h4 class="text-warning">Aviso <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h4><p>Preencha corretamente todos os campos.</p>')

                }else{

                    $.ajax({
                        url: 'src/fornecedor/actions/editar_action.php',
                        method: 'POST',
                        data: {
                            codigo,
                            empresa,
                            cnpj,
                            data_inicio,
                            data_fim,
                            tipo,
                            acao: 'atualizar'
                        },success: function(retorno){
                            popup_att_resultado = $.dialog({
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