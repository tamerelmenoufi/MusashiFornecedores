<?php
    require "../../../lib/config.php";
    global $pdo;

    if(isset($_POST['confirm'])){
        //$update = $pdo->prepare("UPDATE registros_diarios SET status = '0', visivel = '0' WHERE codigo = {$_POST['codigo_registro']}");
        $update = $pdo->prepare("DELETE FROM registros_diarios WHERE codigo = {$_POST['codigo_registro']}");
        $update->execute();

        $update = $pdo->prepare("DELETE FROM assinaturas WHERE codigo_avaliacao_mensal = {$_POST['codigo_registro']}");
        $update->execute();
        
        echo "ok";
        exit;
    }else{
        $sql = $pdo->prepare("SELECT * FROM registros_diarios WHERE codigo = {$_POST['codigo']}");
        $sql->execute();

        if($sql->rowCount() > 0){
            $d = $sql->fetch();
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center text-danger">
                        <h3>AVISO!</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <p>Você esta prestes e excluir um registro, deseja confirmar esta ação?</p>
                    </div>
                    <div class="col-md-12 text-center">
                        <button fechar class="btn btn-outline-success">Não</button>
                        <button
                                confirm="s"
                                data="<?=$d['data_registro']?>"
                                codigo_registro="<?=$_POST['codigo']?>"
                                fornecedor="<?=$_POST['fornecedor']?>"
                                class="btn btn-outline-danger"
                        >Sim</button>
                    </div>
                </div>
            </div>
        <?php
        }
    }

?>
<script>
    $('button[confirm]').click(function(){
        let confirm = $(this).attr('confirm')
        let codigo_registro = $(this).attr('codigo_registro')
        let fornecedor = $(this).attr('fornecedor')
        let data_registro = $(this).attr('data_registro')


        $.ajax({
            url: 'src/fornecedor/actions/excluir_action.php',
            method: "POST",
            data: {
                confirm,
                codigo_registro,
                data_registro
            },
            success: function(exclusao){
                if(exclusao == 'ok'){
                    $.ajax({
                        url: 'src/fornecedor/fornecedor_registros.php',
                        method: "POST",
                        data: {
                            codigo_fornecedor: fornecedor
                        },
                        success: function(retorno){

                            $.ajax({
                                url: "src/fornecedor/actions/mes_action.php",
                                method: "POST",
                                data: {
                                    codigo_fornecedor: fornecedor,
                                    data:data_registro
                                }
                            })

                            $.ajax({
                                url: "src/fornecedor/actions/ano_action.php",
                                method: "POST",
                                data: {
                                    codigo_fornecedor: fornecedor,
                                    data:data_registro
                                }
                            })

                            popup.close()
                            $('div#home').html(retorno)

                        }
                    })




                }
            }
        })
    })

    $('button[fechar]').click(function(){
        popup.close()
    })
</script>