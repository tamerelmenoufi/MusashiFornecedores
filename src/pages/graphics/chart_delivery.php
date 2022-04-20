<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    if(!isset($_POST['mes'])){
        $M = date("m");
    }else{
        $M = $_POST['mes'];
    }

    $array_valores = [];
    $array_codigo = [];
    $array_cor = [];
    $array_border = [];

    $i=0;

    //for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $query = $pdo->prepare("SELECT f.nome,
        f.codigo as fornecedor_codigo,
        ava.ano,
        ava.delivery,
        ava.posicao
        FROM avaliacao_mensal ava
        LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.delivery DESC");
        $query->execute();

        if($query->rowCount() > 0){
            while($d = $query->fetch()) {
                $array_codigo[] =  "'".str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)."'";
                $array_valores[] = "'".$d['delivery']."'";


                if($d['delivery'] < 91.99){
                    $array_cor[] = '"#dc3545"'; /// DEFICIENTE
                    $array_border[] = '"#dc3545"';
                }elseif($d['delivery'] > 92.00 && $d['delivery'] < 95.99){///// REGULAR
                    $array_cor[] = '"#ffc107"';
                    $array_border[] = '"#ffc107"';
                }elseif($d['delivery'] > 96.00  && $d['delivery'] < 98.99){ //// BOM
                    $array_cor[] = '"#007bff"';
                    $array_border[] = '"#6610f2"';
                }elseif($d['delivery'] > 98.99 && $d['delivery'] <= 100.00){ ///OTIMO
                    $array_cor[] = '"#28a745"';
                    $array_border[] = '"#198754"';
                }
            }
        }
    // }
?>


<canvas can id="chart_delivery"></canvas>

<script>
    var ctx10 = document.getElementById('chart_delivery');
    var chart_ano = new Chart(ctx10, {
        type: 'bar',
        data: {
            labels: [
                <?=@implode(",", $array_codigo)?>
            ],
            datasets: [{
                label: ["Delivery"],
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
