<?php
    require "../../..//lib/config.php";

    global $pdo;

    $demerito = 0;
    $quality_ppm = 0;

    if($_POST['tipo_fornecedor'] == "PD"){
        if( $_POST['ppm'] > 500 ){
            $demerito = 20;
            $quality_ppm = 2;
        }else{
            $quality_ppm = 1;
        }
    }else{
        if( $_POST['ppm'] > 22000 && $_POST['qnt_recebida'] >= 5000 ){
            $demerito = 20;
            $quality_ppm = 4;
        }else{
            $quality_ppm = 3;
        }
    }

    $ppm = $_POST['ppm'] == ''?0:$_POST['ppm'];


    $sql = $pdo->prepare("SELECT SUM(ip_emissao.demerito + ip_reincidente.demerito + ip_atraso.demerito) as demeritos
    FROM aux_ip_oficial_emissao ip_emissao
    JOIN aux_ip_reincidente ip_reincidente ON ip_reincidente.codigo = {$_POST['quality_ip_reincidente']}
    JOIN aux_ip_atraso_resposta ip_atraso ON ip_atraso.codigo = {$_POST['quality_atraso_resposta']}
    WHERE ip_emissao.codigo = {$_POST['quality_ip_emitido']}");
    $sql->execute();
    $d = $sql->fetch();

    $demerito += $d['demeritos'];

    $sql = $pdo->prepare("UPDATE registros_diarios SET
    calculo_ppm = {$ppm},
    quality_ip_emitido = {$_POST['quality_ip_emitido']},
    quality_ip_reincidente = {$_POST['quality_ip_reincidente']},
    quality_atraso_resposta = {$_POST['quality_atraso_resposta']},
    total_demerito_quality = {$demerito},
    qnt_devolvida = {$_POST['qnt_devolvida']},
    quality_ppm = {$quality_ppm},
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