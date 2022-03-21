<?php
    require "../../lib/config.php";
    
    global $pdo;

    $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
    $sql->bindValue(':c', $_POST['fornecedor']);
    $sql->execute();

    $d = $sql->fetch();
?>
<div class="container-fluid">
    <div class="row justify-content-between mb-3">
        <div class="col-md-4">
            <h3>Relatório</h3>
        </div>
        <div class="col-md-3 d-flex justify-content-end align-items-center">
            <p class="m-0"><?=date('d/m/Y', strtotime($_POST['data']))?></p>
        </div>
    </div>

    <header class="row mb-2">
        <div class="col-md-4">
            <span class="fw-light">Fornecedor:</span><h5><?=utf8_encode($d['nome'])?></h5>
        </div>
        <div class="col-md-3">
            <span class="fw-light">CNPJ:</span><p><?=$d['cnpj']?></p>
        </div>
        <div class="col-md-2">
            <span class="fw-light">Data de inicio:</span><p><?=date('d/m/Y', strtotime($d['data_inicio']))?></p>
        </div>
        <div class="col-md-3">
            <span class="fw-light">Data de Conclusão:</span><p><?=date('d/m/Y', strtotime($d['data_fim']))?></p>
        </div>
    </header>

    <div class="row mb-2">
        <?php
            $sql = $pdo->prepare("SELECT * FROM registros_diarios rd 
            join aux_ip_oficial_emissao on aux_ip_oficial_emissao.codigo = rd.quality_ip_emitido
            join aux_ip_reincidente on aux_ip_reincidente.codigo = rd.quality_ip_reincidente
            join aux_ip_atraso_resposta on aux_ip_atraso_resposta.codigo = rd.quality_atraso_resposta
            where rd.codigo = :c");
            $sql->bindValue(':c', $_POST['codigo']);
            $sql->execute();

            if($sql->rowCount() > 0){
                $quality = $sql->fetch();
            ?>
            <div class="card col-md-12 p-0 mb-2">
                <div class="card-header fs-5 fw-bolder">
                    Quality

                    <button editar local="src/fornecedor/quality.php" cod="<?=$_POST['codigo']?>" data="<?=$_POST['data']?>" class="btn btn-primary btn-sm pull-right" title="Editar">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="row card-body m-0">
                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_requerida">Quantidade Total do Pedido: </span>
                        <?=$quality['qnt_requerida']?>
                    </div>

                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_recebida">Quantidade Total Recebida: </span>
                        <?=$quality['qnt_recebida']?>
                    </div>

                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Quantidade Devolvida: </span>
                        <?=$quality['qnt_devolvida']?>
                    </div>

                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_devolvida">IP Oficial Emitido: </span>
                        <?=utf8_encode($quality['ip_emissao'])?>
                    </div>

                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_devolvida">IP Reincidente: </span>
                        <?=utf8_encode($quality['ip_reincidente'])?>
                    </div>

                    <div class="col-4 mt-2">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Atraso de Resposta (IP): </span>
                        <?=utf8_encode($quality['atraso'])?>
                    </div>

                    <div class="col-12 mt-2">
                        <span class="mb-1 fw-light" for="qnt_requerida">Devolção em PPM: </span>
                        <?=$quality['calculo_ppm']?> (<?=$d['tipo'] == 'PF'? 'Peças Fundidas':'Padrão'?>)
                    </div>
                </div>
            </div>
            
            <?php
                }else{
            ?> 
                <div class="col-md-12 text-center">
                    <p>Aguardando avaliação Quality</p>
                </div>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM registros_diarios rd 
                join aux_idm_emitidos on aux_idm_emitidos.codigo = rd.delivery_idm_emitidos
                join aux_idm_reincidente on aux_idm_reincidente.codigo = rd.delivery_idm_reincidente
                join aux_idm_atraso_resposta on aux_idm_atraso_resposta.codigo = rd.delivery_atraso_resposta
                join aux_atraso_entrega on aux_atraso_entrega.codigo = rd.delivery_entrega
                join aux_atendimento on aux_atendimento.codigo = rd.delivery_atendimento
                join aux_parada_linha on aux_parada_linha.codigo = rd.delivery_parada_linha
                join aux_comunicacao on aux_comunicacao.codigo = rd.delivery_comunicacao
                where rd.codigo = :c");
                $sql->bindValue(':c', $_POST['codigo']);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $delivery = $sql->fetch();
            ?>
            
            <div class="card col-md-12 p-0 mb-2">
                <div class="card-header fs-5 fw-bolder">
                    Delivery

                    <button editar local="src/fornecedor/delivery.php" cod="<?=$_POST['codigo']?>" data="<?=$_POST['data']?>" class="btn btn-primary btn-sm pull-right" title="Editar">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="row card-body m-0">
                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_requerida">IDM Emitido: </span>
                        <?=utf8_encode($delivery['idm_emitidos'])?>
                    </div>

                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_recebida">IDM Reincidente: </span>
                        <?=utf8_encode($delivery['idm_reincidente'])?>
                    </div>

                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Atraso de Resposta de IDM: </span>
                        <?=utf8_encode($delivery['idm_resp_atraso'])?>
                    </div>

                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Dias de Atraso na Entrega: </span>
                        <?=utf8_encode($delivery['dias_atraso'])?>
                    </div>

                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_devolvida">% Atendimento (mês): </span>
                        <?=utf8_encode($delivery['atendimento'])?>
                    </div>

                    <div class="col-6 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Parada de Linha: </span>
                        <?=utf8_encode($delivery['parada_de_linha'])?>
                    </div>

                    <div class="col-12 mt-2 p-0 ">
                        <span class="mb-1 fw-light" for="qnt_devolvida">Comunicação: </span>
                        <?=utf8_encode($delivery['comunicacao'])?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }else{
        ?>
            <div class="col-md-12 text-center">
                <p>Aguardando avaliação Delivery</p>
            </div>
        <?php
        }
    ?>
    <div class="col-md-12 p-0">
        <button fechar class="btn btn-secondary pull-right">Fechar</button>
    </div>
<script>
    $('button[fechar]').click(function(){
        popup.close()
    })

    $('button[editar]').click(function(){
        let local = $(this).attr('local')
        let codigo = $(this).attr('cod')
        let data = $(this).attr('data')
        let fornecedor = $('input[fornecedor]').attr('fornecedor')
        popup.close()

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
</script>