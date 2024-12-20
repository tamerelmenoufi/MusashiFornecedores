<?php
    require "../../../lib/config.php";
    global $pdo;

    if($_POST['acao'] == 'salvar'){
        $sql = $pdo->prepare("SELECT codigo FROM fornecedores WHERE cnpj = :c AND situacao = 1");
        $sql->bindValue(':c', $_POST['cnpj']);
        $sql->execute();

        if($sql->rowCount() > 0){ 
        ?>      
            <div class="container-fluid">
                <div class="row">
                    <h4 class="text-warning">Aviso <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h4>
                    <p>O CNPJ: <strong><?=$_POST['cnpj']?></strong> encontra-se registrado e em atividade.</p>
                    <div class="col-md-12">
                        <button fechar type="button" class="btn btn-secondary btn-sm pull-right">Fechar</button>
                    </div>
                </div>
            </div>
        <?php
        }else{
            $sql = $pdo->prepare("INSERT INTO fornecedores VALUES ( default, :n, :cnpj, :senha, :t, :di, :df, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1', '0')");

            $sql->bindValue(":n", utf8decode($_POST['empresa']));
            $sql->bindValue(":cnpj", $_POST['cnpj']);
            $sql->bindValue(":senha", $_POST['senha']);
            $sql->bindValue(":t", $_POST['tipo']);
            $sql->bindValue(":di", $_POST['data_inicio']);
            $sql->bindValue(":df", $_POST['data_fim']);

            $sql->execute();

            $lastId = $pdo->lastInsertId()
        ?>
            <div class="container-fluid">
                <form class="row m-0 needs-validation" novalidate>
                    <div class="col-md-12 text-center">
                        <h4 class="text-success">Adicione um contato</i></h4>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>    
                            <input type="text" name="nome" class="form-control" id="nome" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <input type="email" class="form-control" name="email" id="email" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="cpf" class="form-label">CPF:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-id-card" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control cpf"  name="cpf" id="cpf" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="telefone" class="form-label">Contato:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control contato" name="telefone" id="telefone" maxlenght="11" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="setor" class="form-label">Setor:</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fa fa-suitcase" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control" name="setor" id="setor" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button concluir fornecedora="<?=$lastId?>" class="btn btn-success pull-right fw-bolder mb-3" type="button">Cadastrar</button>
                    </div>
                </form>
            </div>
        <?php
        }
    }
?>

<script>
    
    $('.cpf').mask('000.000.000-00')
    $('.contato').mask('(00) 0 0000-0000')

    $('button[concluir]').click(function(){
        // dados do contato\
        let fornecedor = $(this).attr('fornecedora')
        let nome = $('input#nome').val()
        let email = $('input#email').val()
        let cpf = $('input#cpf').val()
        let contato = $('input#telefone').val()
        let setor = $('input#setor').val()
        
        if (fornecedor == "" || nome == "" || email == "" || cpf == "" || contato == "" || setor == "") {

            $.alert('<h4 class="text-warning">Aviso <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h4><p>Preencha corretamente todos os campos.</p>')

        }else{
            $.ajax({
                url: 'src/fornecedor/actions/contato_action.php',
                method: 'POST',
                data: {
                    fornecedor,
                    nome,
                    email,
                    cpf,
                    contato,
                    setor,
                    acao: 'salvar_contato'
                },success: function(retorno){
                    popup_cadastro_resultado.close()
                    $.ajax({
                        url: 'src/fornecedor/fornecedor_lista.php',
                        success: function(retorno){
                            $('div#home').html(retorno)
                        }
                    })
                }
            })
        }
    })

    $('button[fechar]').click(function(){
        popup_cadastro_resultado.close()
    });
</script>