<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    $query = $pdo->prepare("SELECT f.nome,
    f.codigo as fornecedor_codigo,
    ava.ano,
    ava.classificacao,
    ava.quality,
    ava.delivery,
    ava.posicao
    FROM avaliacao_anual ava
    LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
    WHERE ava.ano = '{$Y}' ORDER BY ava.classificacao DESC");
    $query->execute();

    $array_valores = [];
    $array_codigo = [];
    $array_cor = [];
    $array_border = [];

    if($query->rowCount() > 0){
        while($d = $query->fetch()) {
            $array_codigo[] =  "'".str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)."'";
            $array_valores[] = "'".$d['classificacao']."'";


            if($d['classificacao'] < 84.99){
                $array_cor[] = '"#dc3545"'; /// DEFICIENTE
                $array_border[] = '"#dc3545"';
            }elseif($d['classificacao'] > 84.99 && $d['classificacao'] < 93.99){///// REGULAR
                $array_cor[] = '"#ffc107"';
                $array_border[] = '"#ffc107"';
            }elseif($d['classificacao'] > 93.99 && $d['classificacao'] < 98.99){ //// BOM
                $array_cor[] = '"#007bff"';
                $array_border[] = '"#6610f2"';
            }elseif($d['classificacao'] > 98.99 && $d['classificacao'] <= 100.00){ ///OTIMO
                $array_cor[] = '"#28a745"';
                $array_border[] = '"#198754"';
            }
        }
    }
?>


<canvas can id="chart_geral"></canvas>  
    
<script>
    var ctx10 = document.getElementById('chart_geral');
    var chart_ano = new Chart(ctx10, {
        type: 'bar',
        data: {
            labels: [
                <?=@implode(",", $array_codigo)?>
            ],
            datasets: [{
                label: ["GERAL (Q&D)"],
                backgroundColor: [<?=@implode(",", $array_cor)?>],
                borderColor: [<?=@implode(",", $array_border)?>],
                borderWidth: 1,
                data: [<?=@implode(",", $array_valores)?>],
                type: 'bar'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                },
                x: {
                    display: true,
                    offset: true,
                }
            }
        }
    });
</script>
