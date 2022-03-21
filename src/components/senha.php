<?php
    require_once "../../lib/config.php";
    global $pdo;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mt-3 mb-3">
            <h3>Alterar Senha</h3>
        </div>
        <div class="col-md-12 mb-3">
            <label for="senha_atual">Senha Atual</label>
            <input atual type="text" class="form-control" placeholder="..." required>
        </div>
        <div class="col-md-12 mb-3">
            <label for="senha_atual">Nova Senha</label>
            <input nova type="text" class="form-control" placeholder="..." required>
        </div>
        <div class="col-md-12 d-flex justify-content-between mb-3 mt-1">
            <button fechar class="btn btn-danger">Fechar</button>
            <button confirmar cod="<?=$_POST['codigo']?>" class="btn btn-success">Confirmar</button>
        </div>
    </div>
</div>

<script>
    $("button[fechar]").click(function(){
        popup.close()
    })

    $("button[confirmar]").click(function(){
        let codigo = $(this).attr('cod')
        let atual = $("input[atual]").val()
        let nova = $("input[nova]").val()

        if(atual == '' || nova == ''){
            let content = `<div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 p-0 ">
                                        <h3 class="text-danger">Ops...</h3>
                                        <p>Preencha todos os campos!</p>
                                    </div>
                                </div>
                            </div>`
            $.alert(content)
        }else{
            $.ajax({
                url: "src/components/actions/senha_action.php",
                method: "POST",
                data: {
                    codigo,
                    atual,
                    nova,
                    acao: "alterar"
                },success: function(retorno){
                    alerta = $.dialog({
                        content: retorno,
                        title: false,
                        closeIcon: false
                    })
                }
            })
        }
    })
</script>