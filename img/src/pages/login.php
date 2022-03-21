<?php
    require "../../lib/config.php";
?>

<div tela_login class="container-fluid position-absolute h-100">
    <div class="row justify-content-center align-items-center h-100">
        <form class="col-md-3 border" style="border-radius: 15px; overflow: hidden;">
            <!-- <header class="row fw-bolder justify-content-center bg-danger text-white p-2">
                ACESSO AO PAINEL DE CONTROLE
            </header> -->
            <div class=" d-flex justify-content-center p-3">
                <img src="img/musashi.png" style="object-fit: contain;">
            </div>
            <div class="mb-3">
                <label for="user" class="form-label">Nome de usuário:</label>
                <input type="text" class="form-control" id="user" placeholder="">
            </div>
            <div class="mb-4">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha">
                <a href="#" novo_usuario local="src/pages/cadastro.php" style="font-size: 13px">Cadastrar novo usuário!</a>
            </div>

            <button type="button" entrar local="src/pages/actions/login_action.php" class="btn btn-success btn-sm mb-3 w-100">Entrar</button>
        </form>
    </div>
</div>

<script>
    $('button[entrar]').click(function(){
        let local = $(this).attr('local')
        let usuario = $('input#user').val()
        let senha = $('input#senha').val()

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                usuario,
                senha,
                acao: 'logar'
            },success: function(retorno){
                let msg = `<h3 class="text-danger">Aviso!</h3><p>Usuário e/ou senha incorretos!</p>`
                if(retorno == 'erro'){
                    $.alert(msg)
                }else{
                    $.ajax({
                        url: "src/pages/home.php",
                        success: function(home){
                            $('div#body').html(home)
                        }
                    })
                }
            }
        })
    });

    $('a[novo_usuario]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(retorno){
                $('div#body').html(retorno);
            }
        })
    })
</script>