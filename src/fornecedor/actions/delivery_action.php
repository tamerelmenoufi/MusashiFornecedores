<?php
    require "../../..//lib/config.php";

    global $pdo;

    $eficiencia = 100.0;
    $demeritos = 0;

    if($_POST['qnt_recebida'] < $_POST['qnt_requerida']){
        $eficiencia = ($_POST['qnt_recebida']/$_POST['qnt_requerida'])*100;
    }

    $sql = $pdo->prepare("SELECT SUM(idm_emitidos.demerito + idm_reincidente.demerito + atraso_resposta.demerito + atraso_entrega.demerito + atendimento.demerito + parada_linha.demerito + comunicacao.demerito) as demeritos
    FROM aux_idm_emitidos idm_emitidos
    JOIN aux_idm_reincidente idm_reincidente ON idm_reincidente.codigo = {$_POST['delivery_idm_reincidente']}
    JOIN aux_idm_atraso_resposta atraso_resposta ON atraso_resposta.codigo = {$_POST['delivery_atraso_resposta']}
    JOIN aux_atraso_entrega atraso_entrega ON atraso_entrega.codigo = {$_POST['delivery_entrega']}
    JOIN aux_atendimento atendimento ON atendimento.codigo = {$_POST['delivery_atendimento']}
    JOIN aux_parada_linha parada_linha ON parada_linha.codigo = {$_POST['delivery_parada_linha']}
    JOIN aux_comunicacao comunicacao ON comunicacao.codigo = {$_POST['delivery_comunicacao']}
    WHERE idm_emitidos.codigo = {$_POST['delivery_idm_emitidos']}");
    $sql->execute();
    $d = $sql->fetch();

    $demeritos += $d['demeritos'];

    $sql = $pdo->prepare("UPDATE registros_diarios SET
    eficiencia = {$eficiencia},
    qnt_requerida = {$_POST['qnt_requerida']},
    qnt_recebida = {$_POST['qnt_recebida']},
    delivery_idm_emitidos = {$_POST['delivery_idm_emitidos']},
    delivery_idm_reincidente = {$_POST['delivery_idm_reincidente']},
    delivery_atraso_resposta = {$_POST['delivery_atraso_resposta']},
    delivery_entrega = {$_POST['delivery_entrega']},
    delivery_atendimento = {$_POST['delivery_atendimento']},
    delivery_parada_linha = {$_POST['delivery_parada_linha']},
    delivery_comunicacao = {$_POST['delivery_comunicacao']},
    total_demerito_delivery = {$demeritos},
    status = 1
    WHERE codigo = {$_POST['codigo']}");

    $sql->execute();
    ?>
    <div class="container-fluid">
        <h4 class="text-success">Concluido <i class="fa fa-check-square-o" aria-hidden="true"></i></h4>
        <p>Avaliação foi registrada com sucesso.</p>
        <div class="col-md-12 p-0">
            <button concluir codigo_fornecedor="<?=$_POST['fornecedor']?>" local="src/fornecedor/fornecedor_registros.php" data="<?=$_POST['data']?>" class="btn btn-success btn-sm pull-right">Confirmar</button>
        </div>
    </div>

<script>
    $('button[concluir]').click(function(){
        let local = $(this).attr('local')
        let data = $(this).attr('data')
        let codigo_fornecedor = $(this).attr('codigo_fornecedor')

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                codigo_fornecedor
            },success: function(retorno){
                analisePopup.close()
                popup.close()

                $.ajax({
                    url: "src/fornecedor/actions/mes_action.php",
                    method: "POST",
                    data: {
                        codigo_fornecedor,
                        data
                    },
                    success:function(dados){
                        // $.alert(dados);
                    }
                })

                $.ajax({
                    url: "src/fornecedor/actions/ano_action.php",
                    method: "POST",
                    data: {
                        codigo_fornecedor,
                        data
                    }
                })
                $('div#home').html(retorno)
            }
        })
    })
</script>