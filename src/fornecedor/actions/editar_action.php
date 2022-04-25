<?php
    require "../../../lib/config.php";
    global $pdo;

    if($_POST['acao'] == 'atualizar'){
        $sql = $pdo->prepare("UPDATE fornecedores SET 
        nome = :n, 
        cnpj = :cnpj, 
        senha = :senha,               
        tipo = :t, 
        data_inicio = :di, 
        data_fim = :df
        WHERE codigo = {$_POST['codigo']}");

        $sql->bindValue(":n", utf8_decode($_POST['empresa']));
        $sql->bindValue(":cnpj", $_POST['cnpj']);
        $sql->bindValue(":senha", $_POST['senha']);
        $sql->bindValue(":t", $_POST['tipo']);
        $sql->bindValue(":di", $_POST['data_inicio']);
        $sql->bindValue(":df", $_POST['data_fim']);

        $sql->execute();
        ?>
        <div class="container-fluid">
            <h4 class="text-success">Sucesso <i class="fa fa-check-square-o" aria-hidden="true"></i></h4>
            <p>Todas as informações foram atualizadas com sucesso!</p>
            <div class="col-md-12">
                <button concluir local="src/fornecedor/fornecedor_lista.php" type="button" class="btn btn-success btn-sm pull-right">Concluir</button>
            </div>
        </div>
        <?php
    }
?>

<script>
    $('button[concluir]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(retorno){
                popup_att_resultado.close()
                $('div#home').html(retorno)
            }
        })
    })
</script>