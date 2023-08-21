<?php
    require_once "../../lib/config.php";
    
    global $pdo;

    $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
    $sql->bindValue(':c', $_POST['fornecedor']);
    $sql->execute();

    $d = $sql->fetch();

    $rd = $pdo->prepare("SELECT * FROM registros_diarios rd 
    JOIN aux_idm_emitidos aie ON aie.codigo = rd.delivery_idm_emitidos
    JOIN aux_idm_reincidente air ON air.codigo = rd.delivery_idm_reincidente
    JOIN aux_idm_atraso_resposta aar ON aar.codigo = rd.delivery_atraso_resposta
    JOIN aux_atraso_entrega aae ON aae.codigo = rd.delivery_entrega
    JOIN aux_atendimento aa ON aa.codigo = rd.delivery_atendimento
    JOIN aux_parada_linha apl ON apl.codigo = rd.delivery_parada_linha
    JOIN aux_comunicacao ac ON ac.codigo = rd.delivery_comunicacao
    WHERE rd.codigo = :c");
    $rd->bindValue(':c', $_POST['codigo']);
    $rd->execute();

    $avaliacao = $rd->fetch();
?>
<div class="container-fluid">
    <div class="row justify-content-between mb-3">
        <div class="col-md-4">
            <h4>Avaliação Delivery</h4>
        </div>
        <div class="col-md-3 d-flex align-items-center">
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

        <div class="col-6">
            <label class="mb-1 fw-light" for="qnt_requerida">Quantidade Total do Pedido:</label>
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="number" class="form-control" id="qnt_requerida" min="0" value="<?=$avaliacao['qnt_requerida'] > 0? $avaliacao['qnt_requerida']:0?>" required>
            </div>
        </div>

        <div class="col-6">
            <label class="mb-1 fw-light" for="qnt_recebida">Quantidade Total Recebida:</label>
            <div class="input-group">
                <div class="input-group-text"></div>
                <input type="number" class="form-control" id="qnt_recebida" min="0" value="<?=$avaliacao['qnt_recebida'] > 0? $avaliacao['qnt_recebida']:0?>" required>
            </div>
         </div>

        <div class="col-4">
            <label class="mb-1 fw-light" for="delivery_idm_emitidos">IDM Emitido</label>
            <select class="form-select" id="delivery_idm_emitidos">
            <?php
                if($avaliacao['delivery_idm_emitidos'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_idm_emitidos']?>" selected><?=utf8decode($avaliacao['idm_emitidos'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_idm_emitidos");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['idm_emitidos'])?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-4">
            <label class="mb-1 fw-light" for="delivery_idm_reincidente">IDM Reincidente</label>
            <select class="form-select" id="delivery_idm_reincidente">
            <?php
                if($avaliacao['delivery_idm_reincidente'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_idm_reincidente']?>" selected><?=utf8decode($avaliacao['idm_reincidente'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_idm_reincidente");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['idm_reincidente'])?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-4">
            <label class="mb-1 fw-light" for="delivery_atraso_resposta">Atraso de Resposta de IDM</label>
            <select class="form-select" id="delivery_atraso_resposta">
            <?php
                if($avaliacao['delivery_atraso_resposta'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_atraso_resposta']?>" selected><?=utf8decode($avaliacao['idm_resp_atraso'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_idm_atraso_resposta");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['idm_resp_atraso'])?></option>
            <?php
                }
            ?>
            </select>
        </div>
        
        <div class="col-md-12 mt-5">
            <h4>Atendimento</h4>
        </div>
    
        <div class="col-8">
            <label class="mb-1 fw-light" for="delivery_entrega">Dias de Atraso na Entrega</label>
            <select class="form-select" id="delivery_entrega">
            <?php
                if($avaliacao['delivery_entrega'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_entrega']?>" selected><?=utf8decode($avaliacao['dias_atraso'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_atraso_entrega");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['dias_atraso'])?></option>
            <?php
                }
            ?>
            </select>
        </div>
        

        <div class="col-4">
            <label class="mb-1 fw-light" for="delivery_atendimento">% Atendimento (mês)</label>
            <select class="form-select" id="delivery_atendimento">
            <?php
                if($avaliacao['delivery_atendimento'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_atendimento']?>" selected><?=utf8decode($avaliacao['atendimento'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_atendimento");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['atendimento'])?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-6">
            <label class="mb-1 fw-light" for="delivery_parada_linha">Parada de Linha</label>
            <select class="form-select" id="delivery_parada_linha">
            <?php
                if($avaliacao['delivery_parada_linha'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_parada_linha']?>" selected ><?=utf8decode($avaliacao['parada_de_linha'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_parada_linha");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['parada_de_linha'])?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-6">
            <label class="mb-1 fw-light" for="delivery_comunicacao">Comunicação</label>
            <select class="form-select" id="delivery_comunicacao">
            <?php
                if($avaliacao['delivery_comunicacao'] == 0){
            ?>
                <option value="1" selected>...</option>
            <?php
                }else{
            ?>
                <option value="<?=$avaliacao['delivery_comunicacao']?>" selected><?=utf8decode($avaliacao['comunicacao'])?></option>
            <?php
                }

                $sql = $pdo->prepare("SELECT * FROM aux_comunicacao");
                $sql->execute();

                while($options = $sql->fetch()){
            ?>
                <option value="<?=$options['codigo']?>"><?=utf8decode($options['comunicacao'])?></option>
            <?php
                }
            ?>
            </select>
        </div>

        <div class="col-12 d-flex justify-content-between">
            <button concluir codigo="<?=$_POST['codigo']?>" data="<?=$_POST['data']?>" type="button" class="btn btn-success">Concluir</button>
            <button fechar type="button" class="btn btn-danger">Cancelar</button>
        </div>
    </form>
</div>
<script>
    $("button[concluir]").click(function(){
        let codigo = $(this).attr('codigo')
        let data = $(this).attr('data')
        let fornecedor = $('input[fornecedor]').attr('fornecedor')
        let qnt_requerida = $('input#qnt_requerida').val()
        let qnt_recebida = $('input#qnt_recebida').val()
        let delivery_idm_emitidos = $('select#delivery_idm_emitidos').val()
        let delivery_idm_reincidente = $('select#delivery_idm_reincidente').val()
        let delivery_atraso_resposta = $('select#delivery_atraso_resposta').val()
        let delivery_entrega = $('select#delivery_entrega').val()
        let delivery_atendimento = $('select#delivery_atendimento').val()
        let delivery_parada_linha = $('select#delivery_parada_linha').val()
        let delivery_comunicacao = $('select#delivery_comunicacao').val()
        
        if(qnt_requerida == '' || qnt_recebida == ''){
            let content = `<div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <h3 class="text-danger">Ops...</h3>
                                        <p>Preencha todos os campos!</p>
                                    </div>
                                </div>
                            </div>`
            $.alert(content)
        }else{
            $.ajax({
                url: "src/fornecedor/actions/delivery_action.php",
                method: "POST",
                data: {
                    codigo,
                    data,
                    fornecedor,
                    qnt_requerida,
                    qnt_recebida,
                    delivery_idm_emitidos,
                    delivery_idm_reincidente,
                    delivery_atraso_resposta,
                    delivery_entrega,
                    delivery_atendimento,
                    delivery_parada_linha,
                    delivery_comunicacao
                },success: function(retorno){
                    analisePopup = $.dialog({
                        content: retorno,
                        title: false,
                        closeIcon: false,
                        columnClass: 'col-md-offset-6 col-md-4'
                    })
                }
            })
        }
    })

    $('button[fechar]').click(function(){
        popup.close()
    })
</script>