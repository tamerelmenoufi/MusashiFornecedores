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
            $sql = $pdo->prepare("INSERT INTO fornecedores VALUES ( default, :n, :cnpj, :t, :di, :df, :np, :cpfp, :cp, :ep, :sp, :nq, :cpfq, :cq, :eq, :sq, :nd, :cpfd, :cd, :ed, :sd, '1')");

            $sql->bindValue(":n", $_POST['empresa']);
            $sql->bindValue(":cnpj", $_POST['cnpj']);
            $sql->bindValue(":t", $_POST['tipo']);
            $sql->bindValue(":di", $_POST['data_inicio']);
            $sql->bindValue(":df", $_POST['data_fim']);
            $sql->bindValue(":np", $_POST['nome_principal']);
            $sql->bindValue(":cpfp", $_POST['cpf_principal']);
            $sql->bindValue(":cp", $_POST['contato_principal']);
            $sql->bindValue(":ep", $_POST['email_principal']);
            $sql->bindValue(":sp", $_POST['setor_principal']);
            $sql->bindValue(":nq", $_POST['nome_quality']);
            $sql->bindValue(":cpfq", $_POST['cpf_quality']);
            $sql->bindValue(":cq", $_POST['contato_quality']);
            $sql->bindValue(":eq", $_POST['email_quality']);
            $sql->bindValue(":sq", $_POST['setor_quality']);
            $sql->bindValue(":nd", $_POST['nome_delivery']);
            $sql->bindValue(":cpfd", $_POST['cpf_delivery']);
            $sql->bindValue("cd", $_POST['contato_delivery']);
            $sql->bindValue(":ed", $_POST['email_delivery']);
            $sql->bindValue(":sd", $_POST['setor_delivery']);

            $sql->execute();
        ?>
            <div class="container-fluid">
                <h4 class="text-success">Sucesso <i class="fa fa-check-square-o" aria-hidden="true"></i></h4>
                <p>Cadastro efetuado com sucesso!</p>
                <div class="col-md-12">
                    <button concluir local="src/fornecedor/fornecedor_form.php" type="button" class="btn btn-success btn-sm pull-right">Concluir</button>
                </div>
            </div>
        <?php
        }
    }
?>

<script>
    $('button[concluir]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(retorno){ 
                popup_cadastro_resultado.close()
                $('div#home').html(retorno)
            }
        })
    })

    $('button[fechar]').click(function(){
        popup_cadastro_resultado.close()
    });
</script>