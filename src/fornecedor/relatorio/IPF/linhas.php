<?php
    require_once "../../../../lib/config.php";
    global $pdo;

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = $_POST['mes'];
    }else{
        $M = date("M");
    }

    function mesExtenso($mes){
        switch ($mes) {
            case '1':
                return 'Jan';
                break;
            case '2':
                return 'Fev';
                break;
            case '3':
                return 'Mar';
                break;
            case '4':
                return 'Abr';
                break;
            case '5':
                return 'Mai';
                break;
            case '6':
                return 'Jun';
                break;
            case '7':
                return 'Jul';
                break;
            case '8':
                return 'Ago';
                break;
            case '9':
                return 'Set';
                break;
            case '10':
                return 'Out';
                break;
            case '11':
                return 'Nov';
                break;
            case '12':
                return 'Dez';
                break;
        }
    }


    $mes_atual = date("m", mktime(1, 0, 0, date('m'), date('d'), $Y));
    $mes_atual = date("m");
    $total_dias_mes = date("t");

    function diasDoMes(){
        $data_inicio = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
        $dias = [];
        while ($data_inicio <= $data_fim) {
            $dias[] = date('d', $data_inicio);
            $data_inicio = strtotime("+1 day", $data_inicio);
        }
        return $dias;
    }

    function getPercentual($valor, $total){
        if ($total > 0) {
            return round(((int)$valor / (int)$total) * 100, 1) ?: 0;
        } else {
            return 0;
        }
    }


    $query = $pdo->prepare("SELECT f.nome,
    am.mes,
    am.ano,
    am.eficiencia,
    am.quality,
    am.delivery,
    am.classificacao,
    am.posicao,
    am.*,
    (am.quality+am.delivery)/2 as qd,

        (
        SELECT AVG((t2.quality+t2.delivery)/2)
        FROM avaliacao_mensal t2
        WHERE t2.codigo_fornecedor = am.codigo_fornecedor
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) >= -11
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) <= 0
        ) AS IPF,

        (
        SELECT AVG(t2.quality)
        FROM avaliacao_mensal t2
        WHERE t2.codigo_fornecedor = am.codigo_fornecedor
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) >= -11
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) <= 0
        ) AS IQF,

        (
        SELECT AVG(t2.delivery)
        FROM avaliacao_mensal t2
        WHERE t2.codigo_fornecedor = am.codigo_fornecedor
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) >= -11
            AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) <= 0
        ) AS IAF

    FROM `avaliacao_mensal` am
    LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
    where f.codigo = {$_POST['codigo']}
    AND DATE(concat(am.ano, '-', am.mes, '-01')) <= DATE(LAST_DAY(DATE(concat({$Y}, '-', {$M}, '-01'))))
    AND DATE(concat(am.ano, '-', am.mes, '-01')) >= DATE_SUB(concat({$Y}, '-', {$M}, '-01'), INTERVAL 11 MONTH)
    ORDER BY am.ano, am.mes");
    $query->execute();

    $array_valores = [];
    $array_quality = [];
    $array_delivery = [];
    $array_meses = [];
    $array_IPF = [];
    $array_IQF = [];
    $array_IAF = [];


    while ($d = $query->fetch()) {
        $array_meses[] =  '"'.mesExtenso($d['mes']).'"';
        $array_valores[] = $d['classificacao'];
        $array_quality[] = $d['quality'];
        $array_delivery[] = $d['delivery'];
        $array_IPF[] = $d['IPF'];
        $array_IQF[] = $d['IQF'];
        $array_IAF[] = $d['IAF'];

    }
    // se os 12 meses não estiverem preenchidos, preenche os meses com dados vazios
    if (count($array_meses) != 12) {
        $count = 12 - count($array_meses);
        for ($i=$count; $i < 12; $i++) {
            # code...
        }
    }
    if($query->rowCount() > 0){
        $min = min($array_valores);
        $min2 =min($array_quality);
        $min3 = min($array_delivery);
        $minfinal = min($min, $min2, $min3);

        if($minfinal != 100){
            $minfinal = $minfinal-10;
        }else{
            $minfinal = 0;
        }
    }else{
        $minfinal = 0;
    }


?>


<canvas can id="chart_linhas<?=md5(date("YmdHis"))?>" style="height: 100%; width: 100%"></canvas>

<script>
    var ctx10 = document.getElementById('chart_linhas<?=md5(date("YmdHis"))?>');
    var chart_ano = new Chart(ctx10, {
        type: 'line',
        data: {
            labels: [
                <?=@implode(",", $array_meses)?>
            ],
            datasets: [
                {
                label: 'IAF',
                backgroundColor: 'rgb(51,204,51)',
                borderColor: 'rgb(88,204,88)',
                borderWidth: 1,
                data: [<?=@implode(",", $array_IAF)?>],
                stack: 'combined',
                borderWidth: 2
            },
            {
                label: 'IQF',
                backgroundColor: 'rgb(73,116,165)',
                borderColor: 'rgb(73,116,165)',
                borderWidth: 1,
                data: [<?=@implode(",", $array_IQF)?>],
                stack: 'combined',
                borderWidth: 2
            },
            {
                label: 'IPF',
                backgroundColor: 'rgb(204,51,51)',
                borderColor: 'rgb(204,88,88)',
                borderWidth: 2,
                data: [<?=@implode(",", $array_IPF)?>],
                stack: 'combined'
            }

            /*,
            ,
            {
                label: 'DEFICIENTE',
                backgroundColor: '#d11527',
                borderColor: '#d11527',
                borderWidth: 1,
                data: [85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
            },
            {
                label: 'META',
                backgroundColor: 'rgb(33,214,33)',
                borderColor: 'rgb(33,214,33)',
                borderWidth: 1,
                data: [94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
            }*/
        ]
        },
        options: {
            plugins: {
                title: {
                    display: true
                }
            },
            scales: {
                y: {
                    min: <?=$minfinal?>,
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
