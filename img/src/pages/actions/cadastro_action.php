<?php
    require '../../../lib/config.php';

    if($_POST['nome'] && $_POST['email'] && $_POST['usuario'] && $_POST['senha']){
        global $pdo;

        $sql = $pdo->prepare("SELECT codigo FROM login WHERE email = :e");
        $sql->bindValue(':e', $_POST['email']);
        $sql->execute();
    
        if($sql->rowCount() > 0){
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-danger">Ops...</h3>
                        <p>E-mail já cadastrado.</p>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button sing_in type="button" class="btn btn-secondary btn-sm">Fechar</button>
                    </div>
                </div>
            </div>
        <?php
        }else{
            $sql = $pdo->prepare("INSERT INTO login (nome, email, usuario, senha, tipo) VALUES (:n, :e, :u, :s, :t)");
            
            $sql->bindValue(':n', $_POST['nome']);
            $sql->bindValue(':e', $_POST['email']);
            $sql->bindValue(':u', $_POST['usuario']);
            $sql->bindValue(':s', md5($_POST['senha']));
            $sql->bindValue(':t', 2);
            $sql->execute();
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-alert">Concluido</h3>
                        <p>Cadastro efetuado com sucesso.</p>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button login local="src/pages/login.php" type="button" class="btn btn-success btn-sm">Ok</button>
                    </div>
                </div>
            </div>
        <?php
        }
    }else{
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-danger">Ops...</h3>
                    <p>Preencha todos os campos!</p>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button sing_in type="button" class="btn btn-secondary btn-sm">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
?>

<script>
    $('button[login]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(login){
                $('div#body').html(login)
                popup.close()
            }
        })
    })

    $('button[sing_in]').click(function(){
        popup.close()
    })
</script>