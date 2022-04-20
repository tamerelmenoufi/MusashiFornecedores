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

    for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $query = $pdo->prepare("SELECT f.nome,
        f.codigo as fornecedor_codigo,
        ava.ano,
        ava.quality,
        ava.posicao
        FROM avaliacao_anual ava
        LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.quality DESC");
        $query->execute();

        if($query->rowCount() > 0){
            while($d = $query->fetch()) {

                $array_codigo[] =  "'".str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)."'";
                $array_valores[] = "'".$d['quality']."'";

                if($d['quality'] < 77.99){
                    $array_cor[] = '"#dc3545"'; /// DEFICIENTE
                    $array_border[] = '"#dc3545"';
                }elseif($d['quality'] > 78.00 && $d['quality'] < 91.99){///// REGULAR
                    $array_cor[] = '"#ffc107"';
                    $array_border[] = '"#ffc107"';
                }elseif($d['quality'] > 92.00 && $d['quality'] < 98.99){ //// BOM
                    $array_cor[] = '"#007bff"';
                    $array_border[] = '"#6610f2"';
                }elseif($d['quality'] > 98.99 && $d['quality'] <= 100.00){ ///OTIMO
                    $array_cor[] = '"#28a745"';
                    $array_border[] = '"#198754"';
                }
            }
        }
    }
?>


<canvas can id="chart_quality"></canvas>

<script>
    var ctx10 = document.getElementById('chart_quality');
    var chart_ano = new Chart(ctx10, {
        type: 'bar',
        data: {
            labels: [
                <?=@implode(",", $array_codigo)?>
            ],
            datasets: [{
                label: ["Quality"],
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
                    max: 110,
                },
                x: {
                    display: true,
                    offset: true,
                }
            }
        }
    });
</script>
