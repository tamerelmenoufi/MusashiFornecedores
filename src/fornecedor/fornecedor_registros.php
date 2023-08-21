<?php
    require "../../lib/config.php";
    global $pdo;

    if($codigo = $_POST['codigo_fornecedor']){
        $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
        $sql->bindValue(':c', $codigo);
        $sql->execute();

        $d = $sql->fetch();

?>
    <div class="container-fluid" >
        <div class="row justify-content-center align-items-center g-3 m-3">
            <div class="col-md-12 p-0 d-flex align-items-center justify-content-between">
                <h3><i class="fa fa-calendar" aria-hidden="true"></i> Registros Diarios</h3>

                <button voltar type="button" class="btn btn-light fs-6"><i class="fa fa-angle-left" aria-hidden="true"></i> voltar</button>
            </div>

            <div class="col-md-4">
                <span class="fw-light">Fornecedor:</span><h5><?=utf8decode($d['nome'])?> <i class="fa fa-handshake-o" aria-hidden="true"></i></h5>
            </div>
            <input type="hidden" fornecedor="<?=$codigo?>">
            <div class="col-md-4">
                <span class="fw-light">CNPJ:</span><p><?=$d['cnpj']?></p>
            </div>
            <div class="col-md-2">
                <span class="fw-light">Data de inicio:</span><p><?=date('d/m/Y', strtotime($d['data_inicio']))?></p>
                <input type="hidden" inicio="<?=$d['data_inicio']?>">
            </div>
            <div class="col-md-2">
                <span class="fw-light">Data de Conclusão:</span><p><?=date('d/m/Y', strtotime($d['data_fim']))?></p>
                <input type="hidden" fim="<?=$d['data_fim']?>">
            </div>
            <input type="hidden" current_data="<?=date("Y-m-d")?>">
            <div class="card p-0 h-100">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-auto">
                            <label for="data" class="visually-hidden">Data</label>
                            <input type="date" class="form-control" id="data" required>
                        </div>
                        <div class="col-auto">
                            <button registrar local="src/fornecedor/actions/registrar_action.php" class="btn btn-success mb-3" cod="<?=$d['codigo']?>">Registrar</button>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Data</th>
                                <th scope="col">QNT do Pedido</th>
                                <th scope="col">QNT Recebida</th>
                                <th scope="col">Eficiencia</th>
                                <th scope="col">Demérito Quality</th>
                                <th scope="col">Demérito Delivery</th>
                                <th scope="col" style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = $pdo->prepare("SELECT * FROM registros_diarios WHERE codigo_fornecedor = :c AND visivel = 1 ORDER BY data_registro DESC");
                                $sql->bindValue(':c', $codigo);
                                $sql->execute();
                                $count = 1;
                                while($d = $sql->fetch()){
                            ?>
                            <tr>
                                <th scope="row"><?=$count?></th>
                                <td scope="col"><?=date('d/m/Y', strtotime($d['data_registro']))?></td>
                                <td scope="col"><?=$d['qnt_requerida']?></td>
                                <td scope="col"><?=$d['qnt_recebida']?></td>
                                <td scope="col"><?=number_format($d['eficiencia'], 1)?></td>
                                <td scope="col"><?=$d['total_demerito_quality'] == NULL? 0 : $d['total_demerito_quality']?></td>
                                <td scope="col"><?=$d['total_demerito_delivery'] == NULL? 0 : $d['total_demerito_delivery']?></td>
                                <td class="d-flex justify-content-center align-items-center" style="gap: 4px;">
                                    <?php
                                        $diabled_quality = $d['total_demerito_quality'] != NULL? 'disabled' : '';
                                        $diabled_delivery = $d['total_demerito_delivery'] != NULL? 'disabled' : '';
                                    ?>
                                    <button abrir <?=$diabled_delivery?> class="btn btn-success btn-sm" local="src/fornecedor/delivery.php" cod="<?=$d['codigo']?>" data="<?=$d['data_registro']?>" >Delivery</button>
                                    <button abrir <?=$diabled_quality?> class="btn btn-success btn-sm" local="src/fornecedor/quality.php" cod="<?=$d['codigo']?>" data="<?=$d['data_registro']?>" >Quality</button>
                                    <button abrir class="btn btn-dark btn-sm" local="src/fornecedor/relatorio.php" cod="<?=$d['codigo']?>" data="<?=$d['data_registro']?>" >Relatório</button>
                                    <?php
                                        if($ConfUsu['tipo'] == 1){
                                    ?>
                                        <button abrir class="btn btn-danger btn-sm" local="src/fornecedor/actions/excluir_action.php" cod="<?=$d['codigo']?>" data="<?=$d['data_registro']?>">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                    $count++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
    }
?>
<script>
    $('button[registrar]').click(function(){
        let local = $(this).attr('local')
        let codigo = $(this).attr('cod')
        let data = $('input#data').val()
        let atual = $('input[current_data]').attr('current_data');
        let inicio = $('input[inicio]').attr('inicio');
        let fim = $('input[fim]').attr('fim');

        let content = `<div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 p-0">
                                    <h3 class="text-danger">Ops...</h3>
                                    <p>Data invalida!</p>
                                </div>
                            </div>
                        </div>`

        if(data > atual){
            $.alert(content)
        }else if(data != '' && (data < inicio || data > fim)){
            $.alert(content)
        }else if(data == ''){
            $.alert(content)
        }else{
            $.ajax({
                url: local,
                method: "POST",
                data: {
                    codigo_fornecedor: codigo,
                    data
                },success: function(retorno){
                    popup = $.dialog({
                        content: retorno,
                        backgroundDismiss: true,
                        closeIcon: false,
                        title: false
                    })
                }
            })
        }
    })

    $('button[abrir]').click(function(){
        let local = $(this).attr('local')
        let codigo = $(this).attr('cod')
        let data = $(this).attr('data')
        let fornecedor = $('input[fornecedor]').attr('fornecedor')


        $.ajax({
            url: local,
            method: 'POST',
            data: {
                codigo,
                fornecedor,
                data
            },success: function(retorno){
                popup = $.dialog({
                    content: retorno,
                    backgroundDismiss: true,
                    closeIcon: false,
                    title: false,
                    columnClass: 'col-md-offset-1 col-md-10'
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
</script>