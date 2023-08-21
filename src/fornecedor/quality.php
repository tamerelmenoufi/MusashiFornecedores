<?php
    require_once "../../lib/config.php";

    global $pdo;

    $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
    $sql->bindValue(':c', $_POST['fornecedor']);
    $sql->execute();

    $d = $sql->fetch();

    //Essa query só vai retornar um resultado dps que for feita a analise!!!!
    $rd = $pdo->prepare("SELECT * FROM registros_diarios rd
    JOIN aux_ip_oficial_emissao aioe ON aioe.codigo = rd.quality_ip_emitido
    JOIN aux_ip_reincidente air ON air.codigo = rd.quality_ip_reincidente
    JOIN aux_ip_atraso_resposta iap ON iap.codigo = rd.quality_atraso_resposta
    WHERE rd.codigo = :c");
    $rd->bindValue(':c', $_POST['codigo']);
    $rd->execute();

    $avaliacao = $rd->fetch();

    //Query responsavel por buscar a Quantidade requerida e recebida, informada pelo delivery
    $query = $pdo->prepare("SELECT qnt_requerida, qnt_recebida, qnt_devolvida, calculo_ppm, eficiencia FROM registros_diarios WHERE codigo = :c");
    $query->bindValue(':c', $_POST['codigo']);
    $query->execute();
    $qnt = $query->fetch();
?>
<div class="container-fluid">
    <div class="row justify-content-between mb-3">
        <div class="col-md-4">
            <h3>Avaliação Quality</h3>
        </div>
        <div class="col-md-3 d-flex justify-content-end align-items-center">
            <p class="m-0"><?=date('d/m/Y', strtotime($_POST['data']))?></p>
        </div>
    </div>

    <header class="row mb-2">
        <div class="col-md-4">
            <span class="fw-light">Fornecedor:</span><h5><?=utf8decode($d['nome'])?></h5>
        </div>
        <input type="hidden" fornecedor="<?=$_POST['fornecedor']?>">
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

    <form class="row mb-2 g-3 align-items-center">
        <?php
            if($qnt['eficiencia'] == NULL){
        ?>
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                AVISO: o setor de Delivery ainda não informou a Quantidade Requerida e Recebida <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            </div>
        </div>
        <?php
            }
        ?>
        <div class="col-3">
            <label class="mb-1 fw-light" for="qnt_requerida">Quantidade Total do Pedido:</label>
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="text" class="form-control" id="qnt_requerida" min="0" readonly value="<?=$qnt['qnt_requerida'] > 0? $qnt['qnt_requerida']:0?>" required>
            </div>
        </div>

        <div class="col-3">
            <label class="mb-1 fw-light" for="qnt_recebida">Quantidade Total Recebida:</label>
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="text" class="form-control" id="qnt_recebida" min="0" readonly value="<?=$qnt['qnt_recebida'] > 0? $qnt['qnt_recebida']:0?>" required>
            </div>
         </div>

         <?php
            $onlyread = '';
            $disabled = '';
            if($qnt['eficiencia'] == NULL){
                $onlyread = 'readonly';
                $disabled = 'disabled';
            };
         ?>

         <div class="col-3">
            <label class="mb-1 fw-light" for="qnt_devolvida">Quantidade Devolvida:</label>
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="number" <?=$onlyread?> class="form-control" id="qnt_devolvida" min="0" value="<?=$qnt['qnt_devolvida'] > 0? $qnt['qnt_devolvida']:0?>"required>
            </div>
         </div>

         <div class="col-3">
            <label class="mb-1 fw-light" for="ppm">PPM <span>(<?=$d['tipo'] == 'PF'? 'Peças Fundidas':'Padrão'?>)</span>:</label>
            <input type="hidden" tipo="<?=$d['tipo']?>">
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="number" class="form-control" id="ppm" step=".01" min="0" readonly value="<?=$qnt['calculo_ppm'] > 0? $qnt['calculo_ppm']:0?>" required>
            </div>
         </div>

        <div class="col-12">
            <label class="mb-1 fw-light" for="quality_ip_emitido">IP Oficial Emitido</label>
            <select class="form-select" id="quality_ip_emitido">
            <?php
                if($avaliacao['quality_ip_emitido'] == 0){
            ?>
                <option value="1" selected >...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['quality_ip_emitido']?>" selected ><?=utf8decode($avaliacao['ip_emissao'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_ip_oficial_emissao");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=$options['ip_emissao']?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-12">
            <label class="mb-1 fw-light" for="quality_ip_reincidente">IP Reincidente</label>
            <select class="form-select" id="quality_ip_reincidente">
            <?php
                if($avaliacao['quality_ip_reincidente'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['quality_ip_reincidente']?>" selected><?=utf8decode($avaliacao['ip_reincidente'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_ip_reincidente");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=$options['ip_reincidente']?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-12">
            <label class="mb-1 fw-light" for="quality_atraso_resposta">Atraso de Resposta de IP</label>
            <select class="form-select" id="quality_atraso_resposta">
            <?php
                if($avaliacao['quality_atraso_resposta'] == 0){
            ?>
                <option value="1" selected >...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['quality_atraso_resposta']?>" selected ><?=utf8decode($avaliacao['atraso'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_ip_atraso_resposta");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=$options['atraso']?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-12 d-flex justify-content-between">
            <button concluir <?=$disabled?> codigo="<?=$_POST['codigo']?>" data="<?=$_POST['data']?>" type="button" class="btn btn-success">Concluir</button>
            <button fechar type="button" class="btn btn-danger">Cancelar</button>
        </div>
    </form>
</div>
<script>
    $('input#qnt_devolvida').keyup(function(){
        let qnt_recebida = $('input#qnt_recebida').val()
        let qnt_devolvida = $('input#qnt_devolvida').val()

        if(qnt_recebida != '' && qnt_devolvida != ''){
            let ppm = (qnt_devolvida/qnt_recebida)*1000000;
            $('input#ppm').val(ppm.toFixed(0))
        }

    })

    $("button[concluir]").click(function(){
        let codigo = $(this).attr('codigo')
        let data = $(this).attr('data')
        let qnt_devolvida = $('input#qnt_devolvida').val()
        let qnt_recebida = $('input#qnt_recebida').val()
        let ppm = $('input#ppm').val() == ''? 0:$('input#ppm').val()
        let fornecedor = $('input[fornecedor]').attr('fornecedor')
        let tipo_fornecedor = $('input[tipo]').attr('tipo')
        let quality_ip_emitido = $('select#quality_ip_emitido').val()
        let quality_ip_reincidente = $('select#quality_ip_reincidente').val()
        let quality_atraso_resposta = $('select#quality_atraso_resposta').val()

        if(qnt_devolvida == ''){
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
                url: "src/fornecedor/actions/quality_action.php",
                method: "POST",
                data: {
                    codigo,
                    data,
                    fornecedor,
                    qnt_devolvida,
                    qnt_recebida,
                    tipo_fornecedor,
                    ppm,
                    quality_ip_emitido,
                    quality_ip_reincidente,
                    quality_atraso_resposta
                },success: function(retorno){
                    analisePopup = $.dialog({
                        content: retorno,
                        title: false,
                        closeIcon: false,
                        columnClass: 'col-md-offset-8 col-md-4'
                    })
                }
            })
        }
    })

    $('button[fechar]').click(function(){
        popup.close()
    })
</script>