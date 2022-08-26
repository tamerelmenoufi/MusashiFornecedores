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

        $query = $pdo->prepare("SELECT
        count(CASE WHEN ava.qualificacao_ipf = 'OTIMO' THEN 1 ELSE NULL END) as otimo,
        count(CASE WHEN ava.qualificacao_ipf = 'BOM' THEN 1 ELSE NULL END) as bom,
        count(CASE WHEN ava.qualificacao_ipf = 'REGULAR' THEN 1 ELSE NULL END) as regular,
        count(CASE WHEN ava.qualificacao_ipf = 'DEFICIENTE' THEN 1 ELSE NULL END) as deficiente
        FROM avaliacao_mensal ava
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.classificacao DESC");



        $query = $pdo->prepare("SELECT f.nome,
                f.codigo as fornecedor_codigo,
                ava.ano,
                /*ava.classificacao,*/
                ava.quality,
                ava.delivery,
                ((ava.quality+ava.delivery)/2) as classificacao,
                ava.posicao
                FROM avaliacao_mensal ava
                LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
                WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.classificacao DESC");
        $query->execute();

        if($query->rowCount() > 0){
            $d = $query->fetch();

            // $array_valores[0] = $array_valores[0] + $d['otimo'];
            // $array_valores[1] = $array_valores[1] + $d['bom'];
            // $array_valores[2] = $array_valores[2] + $d['regular'];
            // $array_valores[3] = $array_valores[3] + $d['deficiente'];
            if($d['classificacao'] < 84.99){
                $array_valores[3] = $array_valores[3] + 1;
            }elseif($d['classificacao'] > 84.99 && $d['classificacao'] < 93.99){///// REGULAR
                $array_valores[2] = $array_valores[2] + 1;
            }elseif($d['classificacao'] > 93.99 && $d['classificacao'] < 98.99){ //// BOM
                $array_valores[1] = $array_valores[1] + 1;
            }elseif($d['classificacao'] > 98.99 && $d['classificacao'] <= 100.00){ ///OTIMO
                $array_valores[0] = $array_valores[0] + 1;
            }


        }
    }
?>


<canvas can style="" id="pie_geral"></canvas>

<script>
    var ctx10 = document.getElementById('pie_geral');
    var chart_ano = new Chart(ctx10, {
        type: 'pie',
        data: {
            labels: [
                'OTIMO',
                'BOM',
                'REGULAR',
                'DEFICIENTE'
            ],
            datasets: [{
                label: [
                'OTIMO',
                'BOM',
                'REGULAR',
                'DEFICIENTE'
            ],
                backgroundColor: ['#198754', '#007bff', '#ffc107', '#dc3545'],
                borderColor: ['#198754', '#007bff', '#ffc107', '#dc3545'],
                borderWidth: 1,
                data: [<?=@implode(",", $array_valores)?>],
                type: 'pie'
            }]
        },
        options: {
            responsive: true,
        },
    });
</script>
